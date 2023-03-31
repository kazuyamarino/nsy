<?php

namespace System\Middlewares;

use Optimus\Onion\Onion;
use Optimus\Onion\LayerInterface;

class AfterLayer implements LayerInterface
{

	public function __construct()
	{
		/*
		Instantiate a model class here!
		 */
	}

	public function peel(mixed $object, \Closure $next)
	{
		$response = $next($object);

		/*
		Result from after middleware here.
		 */
		$object = 'After Core';
	}
}
