<?php

declare(strict_types=1);

namespace System\Libraries;

/**
 * NSY cURL client.
 *
 * A small, chainable wrapper over ext-curl with secure defaults (TLS
 * verification on, redirect cap, timeouts, HTTP(S)-only protocols). The
 * response body is auto-decoded when the server returns JSON.
 *
 * MultiCurl is intentionally not implemented yet.
 *
 * @example
 *   $curl = new Curl();
 *   $curl->get('https://api.example.com/users', ['page' => 1]);
 *   if ($curl->isSuccess()) { $data = $curl->response(); }
 */
class Curl
{
	/** @var \CurlHandle */
	private $handle;

	/** @var array<int,mixed> */
	private array $options = [];

	/** @var array<string,string> */
	private array $headers = [];

	/** @var array<string,string> */
	private array $responseHeaders = [];

	private int $status = 0;
	private mixed $response = null;
	private string $rawResponse = '';
	private string $error = '';
	private int $errorCode = 0;

	/**
	 * @param array<int,mixed> $options extra CURLOPT_* values merged over defaults
	 */
	public function __construct(array $options = [])
	{
		if (!function_exists('curl_init')) {
			throw new \RuntimeException('ext-curl is not available.');
		}

		$this->handle = curl_init();

		// NOTE: use the union operator (not array_merge) — array_merge renumbers
		// integer keys, which would destroy the CURLOPT_* constants.
		$this->options = $options + [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_MAXREDIRS      => 5,
			CURLOPT_CONNECTTIMEOUT => 10,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_SSL_VERIFYHOST => 2,
			// Only HTTP(S) — blocks file://, gopher://, dict:// … (SSRF hardening).
			CURLOPT_PROTOCOLS       => CURLPROTO_HTTP | CURLPROTO_HTTPS,
			CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
			CURLOPT_USERAGENT      => 'NSY-Curl/1.0',
		];
	}

	public function setHeader(string $name, string $value): self
	{
		$this->headers[$name] = $value;
		return $this;
	}

	public function setOpt(int $option, mixed $value): self
	{
		$this->options[$option] = $value;
		return $this;
	}

	public function setTimeout(int $seconds): self
	{
		return $this->setOpt(CURLOPT_TIMEOUT, $seconds)->setOpt(CURLOPT_CONNECTTIMEOUT, $seconds);
	}

	public function setBasicAuthentication(string $username, string $password): self
	{
		return $this->setOpt(CURLOPT_USERPWD, $username . ':' . $password);
	}

	/** @param array<string,mixed> $query */
	public function get(string $url, array $query = []): self
	{
		if ($query !== []) {
			$url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($query);
		}

		return $this->send('GET', $url, null);
	}

	public function head(string $url): self
	{
		return $this->send('HEAD', $url, null, true);
	}

	public function post(string $url, array|string $data = []): self
	{
		return $this->send('POST', $url, $data);
	}

	public function put(string $url, array|string $data = []): self
	{
		return $this->send('PUT', $url, $data);
	}

	public function patch(string $url, array|string $data = []): self
	{
		return $this->send('PATCH', $url, $data);
	}

	public function delete(string $url, array|string $data = []): self
	{
		return $this->send('DELETE', $url, $data);
	}

	public function status(): int
	{
		return $this->status;
	}

	public function isSuccess(): bool
	{
		return $this->status >= 200 && $this->status < 300;
	}

	/** Decoded body (array/object when the server returned JSON), else the raw string. */
	public function response(): mixed
	{
		return $this->response;
	}

	public function raw(): string
	{
		return $this->rawResponse;
	}

	/** @return array<string,string> */
	public function headers(): array
	{
		return $this->responseHeaders;
	}

	public function error(): string
	{
		return $this->error;
	}

	public function errorCode(): int
	{
		return $this->errorCode;
	}

	public function hasError(): bool
	{
		return $this->errorCode !== 0;
	}

	public function close(): void
	{
		if ($this->handle instanceof \CurlHandle) {
			curl_close($this->handle);
		}
	}

	public function __destruct()
	{
		$this->close();
	}

	/**
	 * @param array<string,mixed>|string|null $data
	 */
	private function send(string $method, string $url, array|string|null $data, bool $noBody = false): self
	{
		$this->responseHeaders = [];
		$this->response = null;
		$this->rawResponse = '';
		$this->error = '';
		$this->errorCode = 0;
		$this->status = 0;

		$opts = $this->options;
		$opts[CURLOPT_URL] = $url;
		$opts[CURLOPT_CUSTOMREQUEST] = strtoupper($method);

		if ($noBody) {
			$opts[CURLOPT_NOBODY] = true;
		}

		if ($data !== null) {
			$opts[CURLOPT_POSTFIELDS] = is_array($data) ? http_build_query($data) : $data;
		}

		if ($this->headers !== []) {
			$formatted = [];
			foreach ($this->headers as $name => $value) {
				$formatted[] = $name . ': ' . $value;
			}
			$opts[CURLOPT_HTTPHEADER] = $formatted;
		}

		$opts[CURLOPT_HEADERFUNCTION] = function ($ch, string $header): int {
			$parts = explode(':', $header, 2);
			if (count($parts) === 2) {
				$this->responseHeaders[strtolower(trim($parts[0]))] = trim($parts[1]);
			}
			return strlen($header);
		};

		curl_setopt_array($this->handle, $opts);

		$raw = curl_exec($this->handle);

		$this->errorCode = curl_errno($this->handle);
		$this->error = curl_error($this->handle);
		$this->rawResponse = is_string($raw) ? $raw : '';
		$this->status = (int) curl_getinfo($this->handle, CURLINFO_RESPONSE_CODE);
		$this->response = $this->decode();

		return $this;
	}

	private function decode(): mixed
	{
		$contentType = strtolower($this->responseHeaders['content-type'] ?? '');

		$looksJson = str_contains($contentType, 'json')
			|| ($this->rawResponse !== '' && ($this->rawResponse[0] === '{' || $this->rawResponse[0] === '['));

		if ($looksJson) {
			$decoded = json_decode($this->rawResponse, true);
			if (json_last_error() === JSON_ERROR_NONE) {
				return $decoded;
			}
		}

		return $this->rawResponse;
	}
}
