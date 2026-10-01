<?php

declare(strict_types=1);

namespace System\Test\Core;

use PHPUnit\Framework\TestCase;
use System\Core\NSY_Paginator;

/**
 * Pagination markup built from a Query Builder paginate() result. All inputs
 * are passed explicitly (path/query) so the output is deterministic and does
 * not depend on $_GET / $_SERVER.
 */
final class PaginatorTest extends TestCase
{
	public function testReturnsEmptyMarkupWhenOnlyOnePage(): void
	{
		$this->assertSame('', NSY_Paginator::render(['current_page' => 1, 'last_page' => 1]));
		$this->assertSame('', NSY_Paginator::render([]));
	}

	public function testPreservesExistingQueryString(): void
	{
		$html = NSY_Paginator::render(
			['current_page' => 2, 'last_page' => 10],
			['path' => '/items', 'query' => ['q' => 'x']]
		);

		// Page 1 drops the page param; other pages append it (HTML-escaped).
		$this->assertStringContainsString('href="/items?q=x"', $html);
		$this->assertStringContainsString('href="/items?q=x&amp;page=3"', $html);
	}

	public function testMarksCurrentPage(): void
	{
		$html = NSY_Paginator::render(
			['current_page' => 3, 'last_page' => 10],
			['path' => '/items', 'query' => []]
		);

		$this->assertStringContainsString('aria-current="page">3<', $html);
	}

	public function testDisablesPrevAndFirstOnFirstPage(): void
	{
		$html = NSY_Paginator::render(
			['current_page' => 1, 'last_page' => 5],
			['path' => '/', 'query' => []]
		);

		$this->assertStringContainsString('is-disabled', $html);
	}

	public function testClampsCurrentPageIntoRange(): void
	{
		$html = NSY_Paginator::render(
			['current_page' => 99, 'last_page' => 5],
			['path' => '/', 'query' => []]
		);

		$this->assertStringContainsString('aria-current="page">5<', $html);
	}

	public function testRendersEllipsisForGaps(): void
	{
		$html = NSY_Paginator::render(
			['current_page' => 10, 'last_page' => 50],
			['path' => '/', 'query' => [], 'window' => 1]
		);

		$this->assertStringContainsString('-ellipsis', $html);
	}

	public function testEscapesClassAttribute(): void
	{
		$html = NSY_Paginator::render(
			['current_page' => 2, 'last_page' => 3],
			['path' => '/', 'query' => [], 'class' => 'p"x']
		);

		$this->assertStringContainsString('p&quot;x', $html);
		$this->assertStringNotContainsString('p"x', $html);
	}
}
