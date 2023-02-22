<?php

namespace System\Middlewares;

use Optimus\Onion\Onion;
use Optimus\Onion\LayerInterface;

class after_lyr implements LayerInterface
{

	public function __construct()
	{
		/*
		Instantiate a model class here!
		 */
	}

	public function peel($object, \Closure $next)
	{
		$response = $next($object);

		/*
		Result from after middleware here.
		 */
		$object = 'After Core';
	}
}
