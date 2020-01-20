<?php
namespace System\Libraries;

/**
* This is the library of NSY Fremwork
* Attention, don't try to change the structure of the code, delete, or change.
* Because there is some code connected to the NSY system. So, be careful.
*/
class Aliases
{

	/**
	* Defined variable for NSY Aliases
	*/
	public function __construct()
	{
		// aliasing Assets class
		class_alias('System\Libraries\Assets', 'Pull');
	}

}
