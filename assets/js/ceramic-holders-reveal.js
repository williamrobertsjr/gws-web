// Scroll reveal for the Ceramic Tool Holders landing page.
// Elements marked [data-reveal] fade/slide in once as they enter the viewport. They are only hidden
// while html.cth-reveal is set (inline script in the page head), so no-JS and reduced-motion
// visitors see everything. Elements that enter together are staggered in DOM order.
(function () {
    'use strict';

    var root = document.documentElement;
    var items = document.querySelectorAll('[data-reveal]');
    if (!items.length) return;

    var STEP_MS = 80;
    var MAX_STEPS = 5;

    function show(el, delay) {
        if (delay) el.style.transitionDelay = delay + 'ms';
        el.classList.add('is-revealed');
        // Drop the delay once done so later hover/filter transitions aren't affected.
        setTimeout(function () { el.style.transitionDelay = ''; }, 900 + delay);
    }

    if (!root.classList.contains('cth-reveal') || !('IntersectionObserver' in window)) {
        items.forEach(function (el) { show(el, 0); });
        root.setAttribute('data-cth-reveal-ready', '');
        return;
    }

    var observer = new IntersectionObserver(function (entries) {
        entries
            .filter(function (entry) { return entry.isIntersecting; })
            .sort(function (a, b) {
                return (a.target.compareDocumentPosition(b.target) & Node.DOCUMENT_POSITION_FOLLOWING) ? -1 : 1;
            })
            .forEach(function (entry, i) {
                show(entry.target, Math.min(i, MAX_STEPS) * STEP_MS);
                observer.unobserve(entry.target);
            });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

    items.forEach(function (el) { observer.observe(el); });
    root.setAttribute('data-cth-reveal-ready', '');
})();
