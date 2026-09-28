<?php

namespace System\Apps\General\Controllers;

use System\Core\Load;
use Carbon\Carbon;
use System\Apps\General\Models\Model_Welcome;
use System\Libraries\Docs;

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
			'welcomeText' => $this->Model_Welcome->welcomeText(), // Call the welcomeText method from Model_Welcome
			'mvcText' => $this->Model_Welcome->mvcText(), // Call the mvcText method from Model_Hello inside the Homepage module
			'date' => Carbon::now(), // Instantiate today's date with Carbon
			'docs' => Docs::all(), // All documentation guides (for the in-app doc index)
			'categories' => Docs::categories() // Docs grouped by category
		];

		// Load MVC view page
		Load::template('Header', $arr); // Header page, Apps/Templates/Header.php
		Load::view(null, 'Index_Welcome', $arr); // Index page, Apps/General/Views/Index_Welcome.php
		Load::template('Footer', $arr); // Footer page, Apps/Templates/Footer.php
	}
}
