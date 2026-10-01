<?php

declare(strict_types=1);

namespace System\Core;

/**
 * Renders pagination markup from a Query Builder paginate() result.
 *
 *   $page = qb('users')->where('active', 1)->paginate(15, $current);
 *   echo paginate_links($page);   // helper
 *   echo NSY_Paginator::render($page);
 *
 * The current query string is preserved (minus the page param) so filters and
 * sorting survive page navigation. Styling is class-based (`.nsy-pagination`)
 * with a default theme in public/assets/css/main.css.
 */
class NSY_Paginator
{
	/**
	 * Build the <nav> markup. Returns '' when there is at most one page.
	 *
	 * @param  array{current_page?:int,last_page?:int} $meta Query Builder paginate() result
	 * @param  array<string,mixed> $options
	 * @return string
	 */
	public static function render(array $meta, array $options = []): string
	{
		$current = (int) ($meta['current_page'] ?? 1);
		$last = (int) ($meta['last_page'] ?? 1);

		if ($last <= 1) {
			return '';
		}

		$current = max(1, min($current, $last));

		$pageParam = (string) ($options['page_param'] ?? 'page');
		$window = max(0, (int) ($options['window'] ?? 2));
		$class = (string) ($options['class'] ?? 'nsy-pagination');
		$path = (string) ($options['path'] ?? (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'));
		$query = $options['query'] ?? $_GET;
		if (!is_array($query)) {
			$query = [];
		}

		$url = static function (int $page) use ($path, $query, $pageParam): string {
			$q = $query;
			unset($q[$pageParam]);
			if ($page > 1) {
				$q[$pageParam] = $page;
			}
			$qs = http_build_query($q);

			return $path . ($qs !== '' ? '?' . $qs : '');
		};

		$items = [];
		$items[] = self::item($class, $url(1), '&laquo;', 'First page', $current <= 1);
		$items[] = self::item($class, $url(max(1, $current - 1)), '&lsaquo;', 'Previous page', $current <= 1);

		// First and last are always shown; the middle is a window around current.
		$pages = [1, $last];
		for ($p = $current - $window; $p <= $current + $window; $p++) {
			if ($p >= 1 && $p <= $last) {
				$pages[] = $p;
			}
		}
		$pages = array_values(array_unique($pages));
		sort($pages);

		$previous = null;
		foreach ($pages as $page) {
			if ($previous !== null && $page > $previous + 1) {
				$items[] = '<span class="' . $class . '-ellipsis" aria-hidden="true">&hellip;</span>';
			}

			if ($page === $current) {
				$items[] = '<span class="' . $class . '-link is-active" aria-current="page">' . $page . '</span>';
			} else {
				$items[] = self::item($class, $url($page), (string) $page, 'Go to page ' . $page, false);
			}

			$previous = $page;
		}

		$items[] = self::item($class, $url(min($last, $current + 1)), '&rsaquo;', 'Next page', $current >= $last);
		$items[] = self::item($class, $url($last), '&raquo;', 'Last page', $current >= $last);

		return '<nav class="' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '" aria-label="Pagination">'
			. implode('', $items)
			. '</nav>';
	}

	/**
	 * One link, or a disabled span when $disabled.
	 */
	private static function item(string $class, string $href, string $label, string $aria, bool $disabled): string
	{
		$class = htmlspecialchars($class, ENT_QUOTES, 'UTF-8');

		if ($disabled) {
			return '<span class="' . $class . '-link is-disabled" aria-disabled="true">' . $label . '</span>';
		}

		return '<a class="' . $class . '-link" href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '"'
			. ' aria-label="' . htmlspecialchars($aria, ENT_QUOTES, 'UTF-8') . '">' . $label . '</a>';
	}
}
