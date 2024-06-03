<?php

namespace System\Apps\General\Controllers;

use System\Core\Load;
use Carbon\Carbon;
use System\Apps\General\Models\Model_Welcome;

class Controller_Welcome extends Load
{

	private $Model_Welcome;

	public function __construct()
	{
		// This line of code essentially creates a new instance of the Model_Welcome class and assigns it to the property Model_Welcome of the current object.
		$this->Model_Welcome = new Model_Welcome;
	}

	public function welcome()
	{
		$arr = [
			'welcome_text' => $this->Model_Welcome->welcome_text(), // Call the welcome_text method from Model_Welcome
			'mvc_text' => $this->Model_Welcome->mvc_text(), // Call the mvc_text method from Model_Hello inside the Homepage module
			'date' => Carbon::now() // Instantiate today's date with Carbon
		];

		// Load MVC view page
		Load::template('Header', $arr); // Header page, Apps/Templates/Header.php
		Load::view(null, 'Index_Welcome', $arr); // Index page, Apps/General/Views/Index_Welcome.php
		Load::template('Footer', $arr); // Footer page, Apps/Templates/Footer.php
	}
}
