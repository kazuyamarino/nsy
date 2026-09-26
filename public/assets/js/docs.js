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
