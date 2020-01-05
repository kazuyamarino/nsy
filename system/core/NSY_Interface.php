<?php
namespace System\Core;

defined('ROOT') OR exit('No direct script access allowed');

/**
 * This is the core of NSY Interface Settings
 * Attention, don't try to change the structure of the code, delete, or change.
 * Because there is some code connected to the NSY system. So, be careful.
 */
interface Migration_Impl
{

    /**
     * Function up()
     */
    public function up();

    /**
     * Function down()
     */
    public function down();

}
