let activeFilter = 'all'; // Track the active category filter
const PART_ENDPOINT_BASE = '/wp-json/gws/v1/series/'; // REST endpoint (relative path)
let partSeries = null; // Variable to hold series from part lookup
let noSeriesEl = null; // "No series found" message inside #series-list

let partFetchController = null; // abort in-flight part lookups
let suppressFetchOnce = false;  // skip one fetch when we just updated partSeries
let forceNoSeries = false; // when true, hide all tiles and show the no-series message

const runFiltersNow = () => {
    const searchTerm = document.getElementById('search-input-series').value.trim().toLowerCase();
    const searchTermPart = document.getElementById('search-input-part').value.trim().toLowerCase();

    // If part input is empty or too short, clear the part filter so all tiles can show
    if (searchTermPart.length < 0) {
        partSeries = null;
        forceNoSeries = false; // show all when input is too short/cleared
    }

    // If user typed a part number (>=3 chars), call REST API and apply the series filter
    const shouldFetch = searchTermPart.length >= 0 && !suppressFetchOnce;
    if (shouldFetch) {
        const requestedPart = searchTermPart; // capture the value at request time

        // Abort any in-flight request before starting a new one
        if (partFetchController) {
            partFetchController.abort();
            partFetchController = null;
        }
        partFetchController = new AbortController();

        fetch(`${PART_ENDPOINT_BASE}${encodeURIComponent(requestedPart)}`, {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin',
            signal: partFetchController.signal,
        })
        .then(res => {
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
        })
        .then(data => {
            // Ignore if the user has since changed/cleared the input
            const currentPart = document.getElementById('search-input-part').value.trim().toLowerCase();
            if (currentPart !== requestedPart) return;

            // Apply the result
            if (data && data.series) {
                partSeries = String(data.series);
                forceNoSeries = false; // we have a match; filter to that series
            } else {
                partSeries = null;
                forceNoSeries = true;  // explicit "no series" for the typed part
            }

            // Re-render immediately but skip refetch
            suppressFetchOnce = true;
            runFiltersNow();
        })
        .catch(err => {
            if (err && err.name === 'AbortError') return; // ignore aborted requests
            console.warn('Part lookup failed:', err);
            // On failure, don't force-hide; leave partSeries as-is
        })
        .finally(() => {
            partFetchController = null;
        });
    }

    const gridItems = document.querySelectorAll('.grid-item');
    gridItems.forEach(item => {
        // If API told us there is no matching series for the current part input,
        // hide everything to surface the "No series found" message.
        if (forceNoSeries) {
            item.style.display = 'none';
            return; // skip the rest of the checks
        }

        const matchesCategory = activeFilter === 'all' || item.classList.contains(activeFilter);

        const seriesNameElem = item.querySelector('.seriesName');
        const seriesText = seriesNameElem ? seriesNameElem.innerText.trim().toLowerCase() : '';

        // Series text match
        const matchesSeries = searchTerm === '' || seriesText.includes(searchTerm);
        // Part number match via REST lookup result
        const itemSeriesAttr = item.getAttribute('data-series');
        const matchesPart = !partSeries || (itemSeriesAttr === String(partSeries));

        if (matchesCategory && matchesSeries && matchesPart) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });

    // Toggle the grid-level "No series found" message based on visible tiles
    const visibleCount = Array.from(gridItems).reduce((acc, el) => acc + (el.style.display !== 'none' ? 1 : 0), 0);
    if (noSeriesEl) {
        if (visibleCount === 0) {
            noSeriesEl.classList.remove('hidden');
        } else {
            noSeriesEl.classList.add('hidden');
        }
    }

    // Reset the one-time suppress flag after rendering
    suppressFetchOnce = false;
};

const filterByCategory = (category) => {
    activeFilter = category; // Update the active filter
    applyFilters(); // Apply combined filters
};

let searchTimeout; // Variable to hold the timeout ID for debouncing

const applyFilters = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(runFiltersNow, 1000); // .5 seconds of inactivity
};

// Event listener for search input
const filterByName = (event) => {
    applyFilters(); // Apply combined filters when typing in the search box
};

// Add event listeners to category buttons
document.addEventListener('DOMContentLoaded', () => {
    const filterButtons = document.querySelectorAll('.filter-btn');

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.getAttribute('data-filter');
            filterByCategory(filter);

            // Highlight the active button
            filterButtons.forEach(btn => btn.classList.remove('is-checked'));
            button.classList.add('is-checked');
        });
    });

    const seriesInput = document.getElementById('search-input-series');
    const partInput = document.getElementById('search-input-part');
    noSeriesEl = document.getElementById('no-series-message');

    if (seriesInput) {
        seriesInput.addEventListener('input', () => {
            const val = seriesInput.value.trim().toLowerCase();
            if (val === '') {
                // If series search is cleared, update immediately
                runFiltersNow();
            } else {
                applyFilters(); // 500ms debounce for normal typing
            }
        });

        // Backspace/Delete clears the entire input and re-runs filters immediately
        seriesInput.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' || e.key === 'Delete') {
                e.preventDefault();
                seriesInput.value = '';
                clearTimeout(searchTimeout);
                runFiltersNow();
            }
        });
    } else {
        console.warn('#search-input not found in DOM');
    }

    if (partInput) {
        partInput.addEventListener('input', () => {
            const val = partInput.value.trim().toLowerCase();
            if (val.length < 3) {
                // Short/cleared part input should clear filter immediately
                partSeries = null;
                forceNoSeries = false;
                runFiltersNow();
            } else {
                applyFilters(); // 500ms debounce for normal typing
            }
        });

        // Backspace/Delete clears the entire input, aborts any fetch, resets filter, and re-runs immediately
        partInput.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' || e.key === 'Delete') {
                e.preventDefault();
                partInput.value = '';
                if (partFetchController) {
                    partFetchController.abort();
                    partFetchController = null;
                }
                partSeries = null;
                forceNoSeries = false;
                clearTimeout(searchTimeout);
                runFiltersNow();
            }
        });
    } else {
        console.warn('#search-input-part not found in DOM');
    }
});

document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('search-input-series').disabled = false;
  document.getElementById('search-input-part').disabled = false;
});
