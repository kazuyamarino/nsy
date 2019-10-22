<?php
namespace Modules\Controllers;

defined('ROOT') OR exit('No direct script access allowed');

use Core\NSY_Controller;
use Models\Model_Welcome;
use Modules\Models\Model_Hello;
use Carbon\Carbon;

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
		// Call my_name method from Model_Welcome
		$d['my_name'] = $this->m_welcome->welcome();

		// Call my_name method from Model_Hello
		$d['hmvc_page'] = $this->m_hello->hmvc_page();

		// Instantiate today date with Carbon
		$d['date'] = Carbon::now();

		// Passing variable into view
		$this->set($d);

		// Load HMVC view page
		$this->load_template("header")->load_view("homepage", "index")->load_template("footer");
	}

}
