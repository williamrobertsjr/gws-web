(function () {
	const overlay = document.getElementById('mega-menu-overlay');
	const dropdown = document.getElementById('mega-menu-dropdown');
	const track = dropdown ? dropdown.querySelector('.mega-menu-track') : null;
	const panel1 = document.getElementById('mega-menu-panel1');
	const panel2 = document.getElementById('mega-menu-panel2');
	const triggerBar = document.getElementById('mega-menu-trigger-bar');
	const hamburger = document.getElementById('mega-menu-hamburger');

	if (!overlay || !dropdown || !track || !panel1 || !panel2 || !triggerBar) return;

	function isDesktop() {
		return window.innerWidth >= 768;
	}

	function positionDropdown() {
		if (!isDesktop()) {
			dropdown.style.top = '';
			dropdown.style.maxHeight = '';
			return;
		}
		const header = document.querySelector('.header');
		const top = header ? header.getBoundingClientRect().bottom : 0;
		dropdown.style.top = top + 'px';
		dropdown.style.maxHeight = Math.max(window.innerHeight - top - 16, 200) + 'px';
	}

	window.addEventListener('resize', positionDropdown);

	function closeMobileTriggerBar() {
		triggerBar.classList.remove('is-open');
		if (hamburger) hamburger.setAttribute('aria-expanded', 'false');
	}

	function closePanel2() {
		track.classList.remove('is-panel2');
		panel2.innerHTML = '';
		panel1.querySelectorAll('.mega-menu-panel1-link.is-active').forEach(function (link) {
			link.classList.remove('is-active');
		});
	}

	function closeDropdown() {
		overlay.classList.remove('is-open');
		dropdown.classList.remove('is-open', 'is-flat');
		overlay.setAttribute('hidden', '');
		dropdown.setAttribute('hidden', '');
		closePanel2();
		panel1.querySelectorAll('.mega-menu-panel1-content.is-active').forEach(function (block) {
			block.classList.remove('is-active');
			block.setAttribute('hidden', '');
		});
		document.querySelectorAll('.mega-menu-trigger.is-active').forEach(function (btn) {
			btn.classList.remove('is-active');
		});
	}

	function closeMenu() {
		closeDropdown();
		closeMobileTriggerBar();
		document.body.classList.remove('mega-menu-is-open');
	}

	function backToTriggerBar() {
		closeDropdown();
		triggerBar.classList.add('is-open');
		if (hamburger) hamburger.setAttribute('aria-expanded', 'true');
	}

	function activateChildLink(childLink) {
		const key = childLink.getAttribute('data-menu-children');
		const activePanel1Content = childLink.closest('.mega-menu-panel1-content');
		const template = activePanel1Content && activePanel1Content.querySelector('template[data-panel2-content="' + key + '"]');
		if (!template) return;

		activePanel1Content.querySelectorAll('.mega-menu-panel1-link.is-active').forEach(function (link) {
			link.classList.remove('is-active');
		});
		childLink.classList.add('is-active');

		panel2.innerHTML = '';
		panel2.appendChild(template.content.cloneNode(true));
		track.classList.add('is-panel2');

		const firstMaterialBtn = panel2.querySelector('[data-pi-material]');
		if (firstMaterialBtn) selectMaterial(firstMaterialBtn);
	}

	function selectMaterial(materialBtn) {
		const materialKey = materialBtn.getAttribute('data-pi-material');
		const piBlock = materialBtn.closest('.mega-menu-pi');
		if (!piBlock) return;

		piBlock.querySelectorAll('[data-pi-material]').forEach(function (btn) {
			btn.classList.toggle('is-active', btn === materialBtn);
		});
		piBlock.querySelectorAll('[data-pi-shapes]').forEach(function (shapeList) {
			const isMatch = shapeList.getAttribute('data-pi-shapes') === materialKey;
			shapeList.toggleAttribute('hidden', !isMatch);
		});
	}

	function openPanel1(key) {
		positionDropdown();

		let isFlat = false;

		panel1.querySelectorAll('.mega-menu-panel1-content').forEach(function (block) {
			const isMatch = block.getAttribute('data-mega-menu-panel1') === key;
			block.classList.toggle('is-active', isMatch);
			if (isMatch) {
				block.removeAttribute('hidden');
				isFlat = block.getAttribute('data-mega-menu-layout') === 'flat';
			} else {
				block.setAttribute('hidden', '');
			}
		});

		document.querySelectorAll('.mega-menu-trigger').forEach(function (btn) {
			btn.classList.toggle('is-active', btn.getAttribute('data-mega-menu-open') === key);
		});

		closePanel2();
		dropdown.classList.toggle('is-flat', isFlat);

		overlay.removeAttribute('hidden');
		dropdown.removeAttribute('hidden');
		overlay.classList.add('is-open');
		dropdown.classList.add('is-open');
		document.body.classList.add('mega-menu-is-open');
		closeMobileTriggerBar();

		if (isDesktop() && !isFlat) {
			const activeBlock = panel1.querySelector('.mega-menu-panel1-content.is-active');
			const firstChildLink = activeBlock && activeBlock.querySelector('[data-menu-children]');
			if (firstChildLink) activateChildLink(firstChildLink);
		}
	}

	document.addEventListener('click', function (event) {
		const trigger = event.target.closest('[data-mega-menu-open]');
		if (trigger) {
			openPanel1(trigger.getAttribute('data-mega-menu-open'));
			return;
		}

		if (hamburger && event.target.closest('#mega-menu-hamburger')) {
			const isOpen = triggerBar.classList.toggle('is-open');
			hamburger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
			return;
		}

		if (event.target.closest('[data-mega-menu-trigger-close]')) {
			closeMobileTriggerBar();
			return;
		}

		if (event.target.closest('[data-mega-menu-trigger-back]')) {
			backToTriggerBar();
			return;
		}

		const childLink = event.target.closest('[data-menu-children]');
		if (childLink) {
			event.preventDefault();
			activateChildLink(childLink);
			return;
		}

		const materialBtn = event.target.closest('[data-pi-material]');
		if (materialBtn) {
			selectMaterial(materialBtn);
			return;
		}

		if (event.target.closest('[data-mega-menu-back]')) {
			closePanel2();
			return;
		}

		if (event.target.closest('[data-mega-menu-close]')) {
			closeMenu();
			return;
		}

		// Header sits above the overlay so the trigger bar stays clickable while
		// the menu is open, which also means clicks on the rest of the header
		// (logo, buttons, the whitespace above the nav row) never reach the
		// overlay's own click-to-close. Treat any other click inside the header
		// as "clicked away" on desktop.
		if (isDesktop() && dropdown.classList.contains('is-open') && event.target.closest('.header') && !event.target.closest('.mega-menu-trigger-bar')) {
			closeMenu();
		}
	});

	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') closeMenu();
	});
})();
