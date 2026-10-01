<?php

declare(strict_types=1);

namespace System\Apps\General\Controllers;

use System\Core\Load;
use Carbon\Carbon;
use System\Apps\General\Models\Model_Welcome;
use System\Libraries\Docs;

class Controller_Welcome extends Load
{
	/**
	 * Render the documentation index page.
	 *
	 * @return void
	 */
	public function welcome(): void
	{
		$model = Load::model(Model_Welcome::class);

		$arr = [
			'welcomeText' => $model->welcomeText(), // Header greeting (Model_Welcome)
			'date' => Carbon::now(),                // Today's date (Carbon)
			'docs' => Docs::all(),                  // All documentation guides
			'categories' => Docs::categories(),     // Docs grouped by category
		];

		Load::template('Header', $arr);            // Apps/Templates/Header.php
		Load::view(null, 'Index_Welcome', $arr);   // Apps/General/Views/Index_Welcome.php
		Load::template('Footer', $arr);            // Apps/Templates/Footer.php
	}
}
