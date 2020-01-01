<?php
namespace Routes;

defined('ROOT') OR exit('No direct script access allowed');

use Core\NSY_Router as route;
use Core\NSY_Desk;

Class Migration
{

    public function __construct()
    {
        // Migration Route
        route::any(
            'migup=(:any)', function ($class) {
                NSY_Desk::mig_up($class);
            }
        );

        route::any(
            'migdown=(:any)', function ($class) {
                NSY_Desk::mig_down($class);
            }
        );
    }

}
