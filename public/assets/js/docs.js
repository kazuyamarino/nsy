/**
 * NSY documentation index search.
 *
 * Filters the card grid on the welcome page
 * (System/Apps/General/Views/Index_Welcome.php) by the data-search attribute.
 * Safe to load globally: it exits early when the search input is absent.
 */
(function () {
	'use strict';

	var input = document.getElementById('nsyDocSearch');
	if (!input) {
		return;
	}

	var cards = Array.prototype.slice.call(document.querySelectorAll('.nsy-card'));
	var sections = Array.prototype.slice.call(document.querySelectorAll('.nsy-cat'));
	var empty = document.getElementById('nsyDocEmpty');

	input.addEventListener('input', function () {
		var q = input.value.trim().toLowerCase();
		var visible = 0;

		cards.forEach(function (card) {
			var haystack = (card.getAttribute('data-search') || '').toLowerCase();
			var show = q === '' || haystack.indexOf(q) !== -1;
			card.style.display = show ? '' : 'none';
			if (show) {
				visible++;
			}
		});

		sections.forEach(function (section) {
			var any = Array.prototype.some.call(section.querySelectorAll('.nsy-card'), function (card) {
				return card.style.display !== 'none';
			});
			section.style.display = any ? '' : 'none';
		});

		if (empty) {
			empty.style.display = visible ? 'none' : '';
		}
	});
}());

/**
 * NSY documentation "On this page" scroll spy.
 *
 * Marks the table-of-contents entry whose section the reader is currently in
 * (System/Apps/General/Views/Index_Docs.php), and keeps it visible inside the
 * sidebar's own scroll area. Safe to load globally: it exits early when the
 * sidebar TOC is absent, e.g. on the welcome page or a doc with no headings.
 */
(function () {
	'use strict';

	var toc = document.querySelector('.nsy-docs-toc');
	if (!toc) {
		return;
	}

	/* Pair every entry with its heading so we can measure it. Entries whose
	   target is missing are dropped instead of silently mis-highlighting. */
	var entries = [];
	Array.prototype.forEach.call(toc.querySelectorAll('.nsy-docs-toc-link'), function (link) {
		var id = (link.getAttribute('href') || '').replace(/^#/, '');
		var heading = id ? document.getElementById(id) : null;
		if (heading) {
			entries.push({ link: link, heading: heading });
		}
	});

	if (!entries.length) {
		return;
	}

	var scroller = toc.closest ? toc.closest('.nsy-docs-side') : null;
	var current = null;
	var queued = false;

	function setCurrent(entry) {
		if (entry === current) {
			return;
		}

		if (current) {
			current.link.classList.remove('active');
			current.link.removeAttribute('aria-current');
		}

		current = entry;
		current.link.classList.add('active');
		current.link.setAttribute('aria-current', 'true');
		reveal(current.link);
	}

	/* Keep the active entry on screen when the TOC is taller than the
	   sidebar. Adjusts the sidebar's scrollTop directly — scrollIntoView()
	   would drag the whole page along with it. */
	function reveal(link) {
		if (!scroller || scroller.scrollHeight <= scroller.clientHeight) {
			return;
		}
		var top = link.offsetTop;
		var bottom = top + link.offsetHeight;
		if (top < scroller.scrollTop) {
			scroller.scrollTop = top - 8;
		} else if (bottom > scroller.scrollTop + scroller.clientHeight) {
			scroller.scrollTop = bottom - scroller.clientHeight + 8;
		}
	}

	function update() {
		queued = false;

		/* A section counts as current once its heading has scrolled past the
		   reading line a little below the top of the viewport. */
		var line = 120;
		var next = entries[0];

		entries.forEach(function (entry) {
			if (entry.heading.getBoundingClientRect().top <= line) {
				next = entry;
			}
		});

		/* The last section can be too short to ever reach the reading line, so
		   once the page is scrolled to the very bottom it gets the marker. */
		var doc = document.documentElement;
		if (doc.scrollHeight - doc.clientHeight > 2 &&
			window.pageYOffset + window.innerHeight >= doc.scrollHeight - 2) {
			next = entries[entries.length - 1];
		}

		setCurrent(next);
	}

	function schedule() {
		if (!queued) {
			queued = true;
			window.requestAnimationFrame(update);
		}
	}

	window.addEventListener('scroll', schedule, { passive: true });
	window.addEventListener('resize', schedule);
	update();
}());

/**
 * Docs sidebar toggle (small screens).
 *
 * The button is CSS-hidden above the mobile breakpoint, so on desktop the
 * sidebar is always visible and this listener never fires meaningfully. On
 * small screens the button swaps .open on the sidebar (CSS-animated), and the
 * expanded state is mirrored to aria-expanded. Clicking a doc link closes the
 * drawer again so the reader lands on the article, not under it.
 */
(function () {
	'use strict';

	var btn = document.getElementById('nsyDocsMenuBtn');
	var side = document.getElementById('nsyDocsSide');
	if (!btn || !side) {
		return;
	}

	function setOpen(open) {
		side.classList.toggle('open', open);
		btn.setAttribute('aria-expanded', open ? 'true' : 'false');
	}

	btn.addEventListener('click', function () {
		setOpen(!side.classList.contains('open'));
	});

	/* A doc link = leave this page; a TOC "#" link = the reader is reading —
	   close the drawer in both cases so content is not obscured. */
	side.addEventListener('click', function (e) {
		if (e.target.closest('a')) {
			setOpen(false);
		}
	});
}());

/**
 * Legacy anchor fallback.
 *
 * Headings used to be authored as "## 1. Connections", which produced anchors
 * like #1-connections. The numbers were stripped so every guide reads the same
 * way, which renamed those anchors to #connections and broke any link written
 * against the old form. When the current hash matches no element, drop a leading
 * "N-" and try once more so those links still land.
 *
 * Nothing happens when the hash already resolves, so in-page navigation and the
 * scroll spy are untouched.
 */
(function () {
	'use strict';

	if (!window.location.hash) {
		return;
	}

	function findLegacyTarget() {
		var id = window.location.hash.slice(1);
		if (!id || document.getElementById(id)) {
			return null;
		}
		var stripped = id.replace(/^\d+-/, '');
		return stripped !== id && document.getElementById(stripped) ? stripped : null;
	}

	function apply() {
		var id = findLegacyTarget();
		var el = id ? document.getElementById(id) : null;
		if (el) {
			el.scrollIntoView();
		}
	}

	window.addEventListener('hashchange', apply);
	apply();
}());

/**
 * Site-wide back to top (the button markup lives in the shared Footer template,
 * so this runs on every page).
 *
 * The control is a plain "#top" anchor, so the jump itself never depends on
 * this script — the browser performs it natively. Here we only reveal the
 * button once the reader is actually down in the article, and upgrade the jump
 * to a smooth scroll unless the reader asked for reduced motion.
 */
(function () {
	'use strict';

	var link = document.querySelector('.nsy-top');
	if (!link) {
		return;
	}

	/* Roughly a screenful: the button is pointless while the top is in view. */
	var SHOW_AFTER = 400;

	/* Deliberately not throttled through requestAnimationFrame: this is a single
	   idempotent class toggle, so the rAF round-trip buys nothing and would make
	   the button depend on the animation frame clock still ticking. */
	function toggle() {
		link.classList.toggle('is-visible', window.pageYOffset > SHOW_AFTER);
	}

	/* The jump itself is left to the browser: "#top" is a fragment the spec
	   resolves to the top of the document, and it works with the script off.
	   Taking it over with scrollTo({behavior:'smooth'}) proved strictly worse —
	   it needs the animation machinery to be available, and when it is not the
	   preventDefault() leaves the click doing nothing at all. */

	/* Cosmetic: drop the "#top" fragment once the jump has happened, so the
	   address bar keeps pointing at the document and a reload does not re-jump. */
	window.addEventListener('hashchange', function () {
		if (window.location.hash !== '#top') {
			return;
		}
		if (window.history && window.history.replaceState) {
			window.history.replaceState(null, '', window.location.pathname + window.location.search);
		}
	});

	window.addEventListener('scroll', toggle, { passive: true });
	toggle();
}());
