<?php
namespace System\Routes;

defined('ROOT') OR exit('No direct script access allowed');

use System\Core\NSY_Router as Route;

Class Api
{

    public function __construct()
    {
        // define API Routes, the params format is :
        // Format = Route::type('url', 'namespace\class_controller@method')
        // Route type : any, get, post, put, delete, options, head

        // Api Route
        // Route::any('data', 'Controllers\Welcome@data'); // Example
    }

}
