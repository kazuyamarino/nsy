<?php
defined('ROOT') OR exit('No direct script access allowed');

class NSY_AliasClass {

	/*
	Aliasing class method
	 */
	public function __construct() {
		// Aliasing NSY_AssetManager class name
		class_alias("Core\NSY_AssetManager", "add");

		// Aliasing Assets class name
		class_alias("Assets", "pull");

		// Aliasing NSY_Router class name
		class_alias("Core\NSY_Router", "route");
	}

}
