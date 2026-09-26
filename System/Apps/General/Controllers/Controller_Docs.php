<?php

namespace System\Apps\General\Controllers;

use Carbon\Carbon;
use System\Apps\General\Models\Model_Welcome;
use System\Core\Load;
use System\Libraries\Docs;

class Controller_Docs extends Load
{
	/**
	 * Render a documentation page from /docs as HTML (in-app viewer).
	 *
	 * @param mixed $slug Document slug, e.g. "router" or "query-builder".
	 */
	public function show($slug = ''): void
	{
		if (is_array($slug)) {
			$slug = (string) reset($slug);
		}

		$slug = strtolower(trim((string) $slug));
		$rendered = Docs::render($slug);

		if ($rendered === null) {
			if (!headers_sent()) {
				http_response_code(404);
			}
		}

		$arr = $this->baseVars();
		$arr['docs'] = Docs::all();
		$arr['categories'] = Docs::categories();
		$arr['doc'] = $rendered['doc'] ?? null;
		$arr['content'] = $rendered['html'] ?? '';
		$arr['toc'] = $rendered['toc'] ?? [];
		$arr['neighbors'] = Docs::neighbors($slug);

		Load::template('Header', $arr); // Header page, Apps/Templates/Header.php
		Load::view(null, 'Index_Docs', $arr); // Doc viewer, Apps/General/Views/Index_Docs.php
		Load::template('Footer', $arr); // Footer page, Apps/Templates/Footer.php
	}

	/**
	 * Shared variables required by the header and footer templates.
	 *
	 * @return array<string,mixed>
	 */
	private function baseVars(): array
	{
		$model = new Model_Welcome();

		return [
			'welcome_text' => $model->welcome_text(),
			'date' => Carbon::now(),
		];
	}
}
