<?php

declare(strict_types=1);

namespace System\Test\Libraries;

use PHPUnit\Framework\TestCase;
use System\Libraries\Curl;

class CurlTest extends TestCase
{
	/** @var resource|null */
	private static $proc;
	private static int $port;
	private static string $docroot;

	public static function setUpBeforeClass(): void
	{
		self::$port    = random_int(20000, 40000);
		self::$docroot = sys_get_temp_dir() . '/nsy_curl_' . bin2hex(random_bytes(4));
		mkdir(self::$docroot, 0775, true);

		file_put_contents(
			self::$docroot . '/index.php',
			'<?php header("Content-Type: application/json"); echo json_encode(['
			. '"method" => $_SERVER["REQUEST_METHOD"], "get" => $_GET, "post" => $_POST,'
			. '"body" => file_get_contents("php://input")]);'
		);

		$descriptors = [1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
		self::$proc = proc_open(
			[PHP_BINARY, '-S', '127.0.0.1:' . self::$port, '-t', self::$docroot],
			$descriptors,
			$pipes
		);

		$deadline = microtime(true) + 5.0;
		while (microtime(true) < $deadline) {
			$conn = @fsockopen('127.0.0.1', self::$port, $errno, $errstr, 0.2);
			if (is_resource($conn)) {
				fclose($conn);
				return;
			}
			usleep(50000);
		}
	}

	public static function tearDownAfterClass(): void
	{
		if (is_resource(self::$proc)) {
			proc_terminate(self::$proc);
			proc_close(self::$proc);
		}
		foreach (glob(self::$docroot . '/*') ?: [] as $file) {
			@unlink($file);
		}
		@rmdir(self::$docroot);
	}

	private function baseUrl(): string
	{
		return 'http://127.0.0.1:' . self::$port . '/';
	}

	public function testGetWithQueryAndJsonDecode(): void
	{
		$curl = new Curl();
		$curl->get($this->baseUrl(), ['a' => '1']);

		$this->assertTrue($curl->isSuccess());
		$this->assertSame(200, $curl->status());
		$this->assertSame('GET', $curl->response()['method']);
		$this->assertSame('1', $curl->response()['get']['a']);

		$curl->close();
	}

	public function testPostFormData(): void
	{
		$curl = new Curl();
		$curl->post($this->baseUrl(), ['name' => 'nsy']);

		$this->assertSame('POST', $curl->response()['method']);
		$this->assertSame('nsy', $curl->response()['post']['name']);

		$curl->close();
	}

	public function testConnectionErrorIsReported(): void
	{
		$curl = new Curl([CURLOPT_CONNECTTIMEOUT => 1, CURLOPT_TIMEOUT => 2]);
		$curl->get('http://127.0.0.1:1/');

		$this->assertTrue($curl->hasError());
		$this->assertNotSame(0, $curl->errorCode());
		$this->assertFalse($curl->isSuccess());

		$curl->close();
	}

	public function testDefaultProtocolsAreHttpAndHttpsOnly(): void
	{
		$curl = new Curl();

		$ref = new \ReflectionProperty(Curl::class, 'options');
		$ref->setAccessible(true);
		$options = $ref->getValue($curl);

		$expected = CURLPROTO_HTTP | CURLPROTO_HTTPS;

		$this->assertSame($expected, $options[CURLOPT_PROTOCOLS] ?? null);
		$this->assertSame($expected, $options[CURLOPT_REDIR_PROTOCOLS] ?? null);

		$curl->close();
	}
}
