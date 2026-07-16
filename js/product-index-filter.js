document.addEventListener('DOMContentLoaded', function () {
    var initializedSections = new WeakSet();

    // Keeps the current filter state in the URL hash (e.g. #milling/non-ferrous/ball-nose/2-flute-ball-nose)
    // via replaceState, so clicking a tile then hitting the browser Back button restores exactly
    // where you left off (a fresh page load re-reads the hash and replays the clicks), and the
    // filtered view is bookmarkable/shareable. replaceState (not pushState) keeps every filter
    // click out of the back-button history -- one Back press should leave the page entirely, not
    // undo one click at a time.
    function updateHash(parts) {
        var hash = parts.filter(Boolean).join('/');
        var newUrl = hash ? '#' + hash : location.pathname + location.search;
        history.replaceState(null, '', newUrl);
    }

    // Restores a category panel's filter state by clicking the matching buttons in sequence,
    // reusing the normal click handlers instead of duplicating their logic.
    function restoreFromHash(categorySection, materialSlug, shapeSlug, itemSlug) {
        if (!materialSlug) return;
        var materialBtn = categorySection.querySelector('.pi-material-btn[data-material="' + materialSlug + '"]');
        if (!materialBtn) return;
        materialBtn.click();

        if (shapeSlug) {
            var shapeLevel = categorySection.querySelector('.pi-level-shape[data-material="' + materialSlug + '"]');
            var shapeBtn = shapeLevel && shapeLevel.querySelector('.pi-shape-btn[data-shape="' + shapeSlug + '"]');
            if (!shapeBtn) return;
            shapeBtn.click();

            if (itemSlug) {
                var itemLevel = categorySection.querySelector('.pi-level-item[data-material="' + materialSlug + '"][data-shape="' + shapeSlug + '"]');
                var itemBtn = itemLevel && itemLevel.querySelector('.pi-item-btn[data-item="' + itemSlug + '"]');
                if (itemBtn) itemBtn.click();
            }
        } else if (itemSlug) {
            // Flat category (e.g. Miscellaneous): item level has no data-shape attribute.
            var flatItemLevel = categorySection.querySelector('.pi-level-item[data-material="' + materialSlug + '"]');
            var flatItemBtn = flatItemLevel && flatItemLevel.querySelector('.pi-item-btn[data-item="' + itemSlug + '"]');
            if (flatItemBtn) flatItemBtn.click();
        }
    }

    function initCategorySection(categorySection) {
        if (initializedSections.has(categorySection)) return;
        initializedSections.add(categorySection);

        var grid = categorySection.querySelector('.tiles-grid');
        if (!grid) return;

        var materialLevel = categorySection.querySelector('.pi-level-material');
        // Categories with no material/shape breakdown (e.g. Inserts) render tiles with no
        // filter UI at all -- nothing to wire up, and the plain flex-wrap grid needs no Isotope.
        if (!materialLevel) return;

        // Only reached once the section's tab panel is visible, so Isotope can measure it correctly.
        var iso = new Isotope(grid, {
            itemSelector: '.grid-item',
            layoutMode: 'fitRows',
            fitRows: { gutter: 0 }
        });

        var shapeLevels = categorySection.querySelectorAll('.pi-level-shape');
        var itemLevels = categorySection.querySelectorAll('.pi-level-item');
        // Categories with no material breakdown deeper than one level (e.g. Miscellaneous)
        // render only material buttons -- no shape/item levels or crumbs exist in the DOM at all.
        var isFlatCategory = shapeLevels.length === 0;

        var crumbReset = categorySection.querySelector('.pi-crumb-reset');
        var crumbMaterial = categorySection.querySelector('.pi-crumb-material');
        var crumbMaterialLabel = categorySection.querySelector('.pi-crumb-material-label');
        var crumbShape = categorySection.querySelector('.pi-crumb-shape');
        var crumbShapeLabel = categorySection.querySelector('.pi-crumb-shape-label');
        var crumbItem = categorySection.querySelector('.pi-crumb-item');
        var crumbItemLabel = categorySection.querySelector('.pi-crumb-item-label');

        var categorySlug = categorySection.getAttribute('data-pi-category');

        function applyFilter(materialSlug, shapeSlug, itemSlug) {
            var classes = [materialSlug, shapeSlug, itemSlug].filter(Boolean);
            var selector = classes.length
                ? classes.map(function (c) { return '.pi-' + c; }).join('')
                : '*';
            iso.arrange({ filter: selector });
            updateHash([categorySlug, materialSlug, shapeSlug, itemSlug]);
        }

        function toggleActive(buttons, matchFn) {
            buttons.forEach(function (btn) {
                var checked = matchFn(btn);
                btn.classList.toggle('is-checked', checked);
                btn.classList.toggle('pi-dimmed', !checked);
            });
        }

        function clearButtons(buttons) {
            buttons.forEach(function (btn) {
                btn.classList.remove('is-checked', 'pi-dimmed');
            });
        }

        function showLevel(levels, matchFn) {
            levels.forEach(function (level) {
                level.classList.toggle('hidden', !matchFn(level));
            });
        }

        function hideAll(levels) {
            levels.forEach(function (level) { level.classList.add('hidden'); });
        }

        function reset() {
            clearButtons(materialLevel.querySelectorAll('.pi-material-btn'));
            crumbMaterial.classList.add('hidden');

            if (!isFlatCategory) {
                hideAll(shapeLevels);
                hideAll(itemLevels);
                shapeLevels.forEach(function (level) { clearButtons(level.querySelectorAll('.pi-shape-btn')); });
                itemLevels.forEach(function (level) { clearButtons(level.querySelectorAll('.pi-item-btn')); });
                crumbShape.classList.add('hidden');
                crumbItem.classList.add('hidden');
            }

            applyFilter(null, null, null);
        }

        function selectMaterial(materialSlug, label) {
            toggleActive(materialLevel.querySelectorAll('.pi-material-btn'), function (btn) {
                return btn.getAttribute('data-material') === materialSlug;
            });

            crumbMaterialLabel.textContent = label;
            crumbMaterial.classList.remove('hidden');

            if (!isFlatCategory) {
                crumbShape.classList.add('hidden');
                crumbItem.classList.add('hidden');
                showLevel(shapeLevels, function (level) {
                    return level.getAttribute('data-material') === materialSlug;
                });
                hideAll(itemLevels);
                shapeLevels.forEach(function (level) { clearButtons(level.querySelectorAll('.pi-shape-btn')); });
                itemLevels.forEach(function (level) { clearButtons(level.querySelectorAll('.pi-item-btn')); });
            }

            // Flat categories (e.g. Miscellaneous) have nothing further to pick -- clicking a
            // material filters the tile grid to it directly.
            applyFilter(materialSlug, null, null);
        }

        function selectShape(materialSlug, shapeSlug, label) {
            var activeShapeLevel = categorySection.querySelector('.pi-level-shape[data-material="' + materialSlug + '"]');
            if (activeShapeLevel) {
                toggleActive(activeShapeLevel.querySelectorAll('.pi-shape-btn'), function (btn) {
                    return btn.getAttribute('data-shape') === shapeSlug;
                });
            }
            showLevel(itemLevels, function (level) {
                return level.getAttribute('data-material') === materialSlug && level.getAttribute('data-shape') === shapeSlug;
            });
            itemLevels.forEach(function (level) { clearButtons(level.querySelectorAll('.pi-item-btn')); });

            crumbShapeLabel.textContent = label;
            crumbShape.classList.remove('hidden');
            crumbItem.classList.add('hidden');

            applyFilter(materialSlug, shapeSlug, null);
        }

        function deselectShape(materialSlug) {
            var activeShapeLevel = categorySection.querySelector('.pi-level-shape[data-material="' + materialSlug + '"]');
            if (activeShapeLevel) {
                clearButtons(activeShapeLevel.querySelectorAll('.pi-shape-btn'));
            }
            hideAll(itemLevels);
            itemLevels.forEach(function (level) { clearButtons(level.querySelectorAll('.pi-item-btn')); });

            crumbShape.classList.add('hidden');
            crumbItem.classList.add('hidden');

            applyFilter(materialSlug, null, null);
        }

        crumbReset.addEventListener('click', reset);

        materialLevel.addEventListener('click', function (event) {
            var btn = event.target.closest('.pi-material-btn');
            if (!btn) return;

            if (btn.classList.contains('is-checked')) {
                reset();
                return;
            }

            selectMaterial(btn.getAttribute('data-material'), btn.textContent);
        });

        shapeLevels.forEach(function (shapeLevel) {
            shapeLevel.addEventListener('click', function (event) {
                var btn = event.target.closest('.pi-shape-btn');
                if (!btn) return;

                var materialSlug = shapeLevel.getAttribute('data-material');

                if (btn.classList.contains('is-checked')) {
                    deselectShape(materialSlug);
                    return;
                }

                selectShape(materialSlug, btn.getAttribute('data-shape'), btn.textContent);
            });
        });

        itemLevels.forEach(function (itemLevel) {
            itemLevel.addEventListener('click', function (event) {
                var btn = event.target.closest('.pi-item-btn');
                if (!btn) return;

                var materialSlug = itemLevel.getAttribute('data-material');
                var shapeSlug = itemLevel.getAttribute('data-shape');

                if (btn.classList.contains('is-checked')) {
                    clearButtons(itemLevel.querySelectorAll('.pi-item-btn'));
                    crumbItem.classList.add('hidden');
                    applyFilter(materialSlug, shapeSlug, null);
                    return;
                }

                toggleActive(itemLevel.querySelectorAll('.pi-item-btn'), function (b) {
                    return b === btn;
                });

                crumbItemLabel.textContent = btn.textContent;
                crumbItem.classList.remove('hidden');

                applyFilter(materialSlug, shapeSlug, btn.getAttribute('data-item'));
            });
        });
    }

    // Top-level category tabs: swap which panel is visible instead of stacking every
    // category on one long scrolling page. Each panel's Isotope grid is initialized lazily,
    // the first time its tab is shown, since Isotope can't measure a display:none container.
    var tabButtons = document.querySelectorAll('.pi-tab-btn');
    var tabPanels = document.querySelectorAll('.pi-tab-panel');

    // A plain tab switch always starts that category fresh (no leftover material/shape/item
    // selection from an earlier visit this session) -- clicks its own reset button, reusing the
    // normal reset logic instead of duplicating it. Only the hash-restore path (browser Back,
    // or loading a bookmarked filtered URL) re-applies a specific state, immediately after this
    // runs -- see restoreFromHash below.
    function resetCategoryFilters(tabSlug) {
        var panel = document.querySelector('.pi-tab-panel[data-pi-tab="' + tabSlug + '"]');
        var resetBtn = panel && panel.querySelector('.pi-crumb-reset');
        if (resetBtn) resetBtn.click();
    }

    function showTab(tabSlug) {
        tabButtons.forEach(function (btn) {
            var checked = btn.getAttribute('data-pi-tab') === tabSlug;
            btn.classList.toggle('is-checked', checked);
        });
        tabPanels.forEach(function (panel) {
            var isMatch = panel.getAttribute('data-pi-tab') === tabSlug;
            panel.classList.toggle('hidden', !isMatch);
            if (isMatch) {
                panel.querySelectorAll('.product-index-cat').forEach(initCategorySection);
            }
        });
        resetCategoryFilters(tabSlug);
    }

    tabButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (btn.classList.contains('is-checked')) return;
            var tabSlug = btn.getAttribute('data-pi-tab');
            showTab(tabSlug);
            updateHash([tabSlug]);
        });
    });

    var hashParts = location.hash.replace(/^#/, '').split('/').filter(Boolean);
    var hashTabSlug = hashParts[0];
    var hashTabButton = hashTabSlug && document.querySelector('.pi-tab-btn[data-pi-tab="' + hashTabSlug + '"]');

    if (hashTabButton) {
        showTab(hashTabSlug);
        var hashPanel = document.querySelector('.pi-tab-panel[data-pi-tab="' + hashTabSlug + '"]');
        var hashSection = hashPanel && hashPanel.querySelector('.product-index-cat');
        if (hashSection) {
            restoreFromHash(hashSection, hashParts[1], hashParts[2], hashParts[3]);
        }
    } else {
        var initialPanel = document.querySelector('.pi-tab-panel:not(.hidden)');
        if (initialPanel) {
            initialPanel.querySelectorAll('.product-index-cat').forEach(initCategorySection);
        }
    }
});
