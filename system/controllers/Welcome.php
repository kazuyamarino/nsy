<?php
namespace Controllers;

defined('ROOT') OR exit('No direct script access allowed');

use Core\NSY_Controller;
use Carbon\Carbon;
use Models\Model_Welcome;
use Modules\Homepage\Models\Model_Hello;

class Welcome extends NSY_Controller
{

    public function __construct()
    {
        // Instantiate Model_Welcome class
        $this->m_welcome = new Model_Welcome;

        // Instantiate Model_Hello class
        $this->m_hello = new Model_Hello;
    }

    public function index()
    {
        $arr = [
	        'my_name' => $this->m_welcome->welcome(), // Call my_name method from Model_Welcome
	        'mvc_page' => $this->m_hello->mvc_page(), // Call mvc_page method from Model_Hello
	        'date' => Carbon::now() // Instantiate today date with Carbon
        ];

        // Load MVC view page
        $this->load_template('header', $arr)->load_view(null, 'index', $arr)->load_template('footer', $arr);
    }

}
