<?php

declare(strict_types=1);

namespace System\Test\Libraries;

use PHPUnit\Framework\TestCase;
use System\Libraries\Request;

/**
 * Request handling: HTTP method/content-type detection and the typed accessors
 * (which delegate to Validate). Request params are injected directly so the
 * tests do not depend on PHP's filter_input() being available (it is not on the
 * CLI SAPI).
 */
final class RequestTest extends TestCase
{
	protected function tearDown(): void
	{
		unset($_SERVER['REQUEST_METHOD'], $_SERVER['CONTENT_TYPE'], $_SERVER['HTTP_CONTENT_TYPE']);
	}

	private static function withParams(array $params, int|string $key = 0): Request
	{
		$request = new Request();

		$paramsProp = new \ReflectionProperty(Request::class, 'params');
		$paramsProp->setAccessible(true);
		$paramsProp->setValue($request, $params);

		$keyProp = new \ReflectionProperty(Request::class, 'key');
		$keyProp->setAccessible(true);
		$keyProp->setValue($request, $key);

		return $request;
	}

	public function testMethodDetection(): void
	{
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$this->assertTrue(Request::isGet());
		$this->assertFalse(Request::isPost());

		$_SERVER['REQUEST_METHOD'] = 'POST';
		$this->assertTrue(Request::isPost());

		$_SERVER['REQUEST_METHOD'] = 'PUT';
		$this->assertTrue(Request::isPut());

		$_SERVER['REQUEST_METHOD'] = 'DELETE';
		$this->assertTrue(Request::isDelete());
	}

	public function testContentTypeIgnoresParameters(): void
	{
		$_SERVER['CONTENT_TYPE'] = 'application/json; charset=utf-8';
		$this->assertSame('application/json', Request::getContentType());

		unset($_SERVER['CONTENT_TYPE']);
		$_SERVER['HTTP_CONTENT_TYPE'] = 'text/plain';
		$this->assertSame('text/plain', Request::getContentType());

		unset($_SERVER['HTTP_CONTENT_TYPE']);
		$this->assertSame('', Request::getContentType());
	}

	public function testInputReturnsRequestFactory(): void
	{
		$factory = Request::input('GET');

		$this->assertIsCallable($factory);
		$this->assertInstanceOf(Request::class, $factory());
		$this->assertInstanceOf(Request::class, $factory('id'));
	}

	public function testAsStringStripsTags(): void
	{
		$request = self::withParams(['name' => '<b>Bob</b>'], 'name');

		$this->assertSame('Bob', $request->asString());
	}

	public function testAsIntegerReadsSingleKey(): void
	{
		$request = self::withParams(['id' => '7'], 'id');

		$this->assertSame(7, $request->asInteger());
	}

	public function testAsArrayAppliesTypeFilters(): void
	{
		$request = self::withParams(['age' => '42', 'email' => 'nope']);

		$out = $request->asArray(['age' => 'integer', 'email' => 'email']);

		$this->assertSame(42, $out['age']);
		$this->assertNull($out['email']);
	}

	public function testAsObjectWithFilters(): void
	{
		$request = self::withParams(['age' => '42']);

		$out = $request->asObject(['age' => 'integer']);

		$this->assertIsObject($out);
		$this->assertSame(42, $out->age);
	}
}
