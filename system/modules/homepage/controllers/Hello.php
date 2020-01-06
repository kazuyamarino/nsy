<?php
namespace System\Modules\Homepage\Controllers;

defined('ROOT') OR exit('No direct script access allowed');

use System\Core\NSY_Controller;
use System\Models\Model_Welcome;
use System\Modules\Homepage\Models\Model_Hello;
use System\Libraries\Cookie;

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
        $arr = [
	        'my_name' => $this->m_welcome->welcome(), // Call my_name method from Model_Welcome
	        'hmvc_page' => $this->m_hello->hmvc_page(), // Call hmvc_page method from Model_Hello
	        'date' => Carbon::now() // Instantiate today date with Carbon
        ];

        // Load HMVC view page
        $this->load_template('header', $arr)->load_view('homepage', 'index', $arr)->load_template('footer', $arr);
    }

}
