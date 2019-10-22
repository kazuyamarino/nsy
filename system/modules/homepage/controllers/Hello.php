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

$val = "";

$var1 = "";
$var2 = " ";
$var3 = false;
$var4 = true;
$var5 = array();
$var6 = null;
$var7 = "0";
$var8 = 0;
$var9 = 0.0;
$var10 = $val;

for ($x = 1; $x <= 10; $x++) {
  if ( not_filled(${"var$x"}) ) {
    echo 'not_filled => $var'.$x.' = no value<br>';
  } else {
    echo 'not_filled => $var'.$x.' = valued<br>';
  }
}

echo "=================<br>";

for ($x = 1; $x <= 10; $x++) {
  if ( is_filled(${"var$x"}) ) {
    echo 'is_filled => $var'.$x.' = valued<br>';
  } else {
    echo 'is_filled => $var'.$x.' = no value<br>';
  }
}
exit;
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
