// Product families filter for the Ceramic Tool Holders landing page
// (views/partials/ceramic-holders/product-families.twig).
//
// Works like product-index-filter.js on /products/: one active filter button at a time, the
// current filter kept in the URL hash (#round, #rhombic, #square) via replaceState, so Back
// restores it and the filtered view can be bookmarked or shared, and a hash on page load is
// replayed. Cards sit in a CSS grid and are shown/hidden with the `hidden` class, which keeps
// each row's cards the same height as in the design.
document.addEventListener('DOMContentLoaded', function () {
    var section = document.querySelector('[data-cth-families]');
    if (!section) {
        document.documentElement.classList.remove('cth-deeplink');
        return;
    }

    var buttons = section.querySelectorAll('[data-cth-filter]');
    var cards = section.querySelectorAll('[data-cth-shape]');
    var status = section.querySelector('[data-cth-status]');
    var filterSlugs = Array.prototype.map.call(buttons, function (btn) {
        return btn.getAttribute('data-cth-filter');
    });

    // replaceState, not pushState: filter clicks shouldn't each add a Back-button entry.
    function updateHash(slug) {
        var url = slug === 'all' ? location.pathname + location.search : '#' + slug;
        history.replaceState(null, '', url);
    }

    function applyFilter(slug) {
        var shown = 0;

        buttons.forEach(function (btn) {
            var active = btn.getAttribute('data-cth-filter') === slug;
            btn.setAttribute('aria-pressed', active ? 'true' : 'false');
            btn.classList.toggle('is-checked', active);
        });

        cards.forEach(function (card) {
            var match = slug === 'all' || card.getAttribute('data-cth-shape') === slug;
            card.classList.toggle('hidden', !match);
            if (match) shown++;
        });

        if (status) {
            status.textContent = 'Showing ' + shown + ' of ' + cards.length + ' holder styles';
        }
    }

    buttons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var slug = btn.getAttribute('data-cth-filter');
            // Clicking the active filter again clears it, like the /products/ drill-down buttons.
            if (btn.getAttribute('aria-pressed') === 'true' && slug !== 'all') {
                slug = 'all';
            }
            applyFilter(slug);
            updateHash(slug);
        });
    });

    var hashSlug = location.hash.replace(/^#/, '');
    if (hashSlug !== 'all' && filterSlugs.indexOf(hashSlug) !== -1) {
        applyFilter(hashSlug);
    }
    // Reveal the grid: page-ceramic-tool-holders-turning.twig hides it for deep links until now.
    document.documentElement.classList.remove('cth-deeplink');
});
