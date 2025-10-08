let activeFilter = 'all'; // Track the active category filter

// Function to filter items by category
const filterByCategory = (category) => {
    activeFilter = category; // Update the active filter
    applyFilters(); // Apply combined filters
};

// Function to filter items by search term and active category
const applyFilters = () => {
    const searchTerm = document.getElementById('search-input').value.trim().toLowerCase();
    const gridItems = document.querySelectorAll('.grid-item');

    gridItems.forEach(item => {
        const matchesCategory = activeFilter === 'all' || item.classList.contains(activeFilter);
        const seriesNameElem = item.querySelector('.seriesName');
        const matchesSearch = seriesNameElem && seriesNameElem.innerText.toLowerCase().includes(searchTerm);

        // Show the item only if it matches both the category and the search term
        if (matchesCategory && matchesSearch) {
            item.style.display = ''; // Show item
        } else {
            item.style.display = 'none'; // Hide item
        }
    });
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
});
