<?php
namespace System\Models;

defined('ROOT') OR exit('No direct script access allowed');

use System\Core\NSY_Model;

class Model_Welcome extends NSY_Model
{

	public function welcome()
	{
		return 'Welcome to NSY PHP Framework';
	}

}
