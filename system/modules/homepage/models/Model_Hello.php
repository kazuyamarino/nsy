<?php
namespace System\Modules\Homepage\Models;

defined('ROOT') OR exit('No direct script access allowed');

use System\Core\NSY_Model;

class Model_Hello extends NSY_Model
{

    public function mvc_page()
    {
        return 'This is MVC page';
    }

    public function hmvc_page()
    {
        return 'This is HMVC page';
    }

}
