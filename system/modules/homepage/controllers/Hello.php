<?php
namespace Modules\Homepage\Controllers;

defined('ROOT') OR exit('No direct script access allowed');

use Core\NSY_Controller;
use Carbon\Carbon;
use Libraries\Cookie;
use Models\Model_Welcome;
use Modules\Homepage\Models\Model_Hello;

class Hello extends NSY_Controller
{

	public function __construct()
	{
		// Instantiate Model_Welcome class
		$this->m_welcome = new Model_Welcome;

		// Instantiate Model_Hello class
		$this->m_hello = new Model_Hello;
	}

	public function index_hmvc()
	{
		$arr = [
			'my_name' => $this->m_welcome->welcome(), // Call my_name method from Model_Welcome
			'hmvc_page' => $this->m_hello->hmvc_page(), // Call hmvc_page method from Model_Hello
			'date' => Carbon::now() // Instantiate today date with Carbon
		];

		// Load HMVC view page
		$this->load_template('header', $arr)->load_view('homepage', 'index', $arr)->load_template('footer', $arr);
	}

}
