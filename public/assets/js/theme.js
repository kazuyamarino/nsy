/**
 * NSY theme toggle (light / dark) + one-off hint callout.
 *
 * The theme itself is resolved before first paint by a tiny inline script in the
 * site header (System/Apps/Templates/Header.php). This file wires the button
 * (flip `data-theme` on <html>, persist the choice, sync ARIA) and shows a short
 * callout next to it once per session so visitors notice the control.
 *
 * Safe to load globally: it exits early when the button is absent.
 */
(function () {
	'use strict';

	var root = document.documentElement;
	var button = document.getElementById('nsyThemeToggle');
	if (!button) {
		return;
	}

	var KEY = 'nsy-theme';

	/* ---------------------------------------------------------------- theme */

	function isDark() {
		return root.getAttribute('data-theme') === 'dark';
	}

	function sync() {
		var dark = isDark();
		button.setAttribute('aria-pressed', dark ? 'true' : 'false');
		button.setAttribute('title', dark ? 'Switch to light theme' : 'Switch to dark theme');
	}

	button.addEventListener('click', function () {
		var dark = !isDark();

		if (dark) {
			root.setAttribute('data-theme', 'dark');
		} else {
			root.removeAttribute('data-theme');
		}

		try {
			localStorage.setItem(KEY, dark ? 'dark' : 'light');
		} catch (e) {
			/* private mode / disabled storage: the toggle still works for this page */
		}

		sync();
		dismissHint();
	});

	sync();

	/* ---------------------------------------------------- hint callout */

	/* Reuses the .nsy-callout component already styled in main.css (the same one
	   the Releases button uses), so no markup or extra CSS is needed. Shown once
	   per session, a moment after the page opens, and dismissed on interaction. */
	var HINT_KEY = 'nsy-theme-hint';
	var hint = null;
	var hintDelay = null;
	var hintTimer = null;
	var hintDismissed = false;

	function dismissHint() {
		hintDismissed = true;
		clearTimeout(hintDelay);
		clearTimeout(hintTimer);
		hintDelay = null;
		hintTimer = null;
		if (hint) {
			hint.classList.remove('show');
		}
	}

	function buildHint() {
		hint = document.createElement('div');
		hint.className = 'nsy-callout';
		hint.textContent = 'Light / dark mode';
		document.body.appendChild(hint);
		return hint;
	}

	function showHint() {
		if (hintDismissed || !document.body) {
			return;
		}

		var el = hint || buildHint();
		var r = button.getBoundingClientRect();
		var cw = el.offsetWidth || 130;
		var ch = el.offsetHeight || 36;
		var center = r.left + r.width / 2;

		/* The toggle lives in the top-right corner: default to below it, and
		   only flip above when the viewport has no room underneath. */
		var placement = 'bottom';
		var x = r.right - cw;
		var y = r.bottom + 10;

		if (y + ch > window.innerHeight - 8) {
			placement = 'top';
			y = r.top - 10 - ch;
		}

		/* Clamp horizontally so the callout never leaves the viewport. */
		x = Math.max(8, Math.min(x, window.innerWidth - cw - 8));

		el.style.left = x + 'px';
		el.style.top = y + 'px';
		el.style.setProperty('--arrow-x', Math.max(14, Math.min(center - x, cw - 14)) + 'px');
		el.dataset.placement = placement;
		el.classList.add('show');
		hintTimer = setTimeout(dismissHint, 5000);
	}

	try {
		if (!sessionStorage.getItem(HINT_KEY)) {
			sessionStorage.setItem(HINT_KEY, '1');
			hintDelay = setTimeout(showHint, 1500);
		}
	} catch (e) {
		/* storage unavailable: skip the hint rather than repeat it every load */
	}

	button.addEventListener('mouseenter', dismissHint);
	window.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') {
			dismissHint();
		}
	});
}());
