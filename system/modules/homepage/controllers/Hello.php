<?php
namespace Modules\Controllers;

defined('ROOT') OR exit('No direct script access allowed');

use Core\NSY_Controller;
use Modules\Models\Model_Hello;
use Carbon\Carbon;

class Hello extends NSY_Controller
{

	public function index_hmvc()
	{
		$d['date'] = Carbon::now();
		$this->set($d);

		$this->load_template("header")->load_hview("index")->load_template("footer");
	}

}
