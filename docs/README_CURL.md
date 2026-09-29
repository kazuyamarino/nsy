# NSY Curl Library — User Tutorial

Native HTTP client at `System/Libraries/Curl.php` (namespace
`System\Libraries`, class `Curl`). Replaces the former `php-curl-class`
dependency and wraps `ext-curl` with secure defaults: TLS verification on,
redirect cap of 5, connect timeout 10s / total timeout 30s.

## Quick Start

```php
use System\Libraries\Curl;

$curl = new Curl();
$curl->get('https://api.example.com/users', ['page' => 1]);

if ($curl->isSuccess()) {
    $data = $curl->response();   // array when the server returned JSON
}
$curl->close();
```

## Requests

```php
$curl->get('https://api.example.com/users', ['page' => 1]); // ?page=1
$curl->post('https://api.example.com/users', ['name' => 'Ana']);
$curl->put('https://api.example.com/users/1', ['name' => 'Ana']);
$curl->patch('https://api.example.com/users/1', ['active' => 1]);
$curl->delete('https://api.example.com/users/1');
$curl->head('https://example.com');
```

Each call returns `$this`, so you can chain:

```php
$data = (new Curl())
    ->setHeader('Accept', 'application/json')
    ->setBasicAuthentication('user', 'pass')
    ->setTimeout(15)
    ->get('https://api.example.com/me')
    ->response();
```

## Reading the Response

| Accessor | Meaning |
| --- | --- |
| `response()` | Decoded body (array when JSON) or raw string |
| `raw()` | Raw body string |
| `status()` | HTTP status code |
| `isSuccess()` | `status` in 200–299 |
| `headers()` | Response headers (lower-cased keys) |
| `error()` / `errorCode()` / `hasError()` | cURL transport error |

JSON bodies are auto-decoded (via `Content-Type` or a leading `{`/`[`):

```php
$curl->get('https://api.example.com/user/1');
$user = $curl->response();          // ['id' => 1, 'name' => 'Ana']
```

## Errors

Transport failures (DNS, connection, timeout) set an error code; they do **not**
throw:

```php
$curl->get('https://unreachable.invalid/');
if ($curl->hasError()) {
    echo $curl->errorCode() . ': ' . $curl->error();
}
```

HTTP error statuses (404/500) are not transport errors — inspect `status()`.

## Options

```php
$curl = new Curl([
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_FOLLOWLOCATION => false,
]);
$curl->setOpt(CURLOPT_SSL_VERIFYPEER, true);
```

> `MultiCurl` (parallel requests) is intentionally not implemented yet.

## Quick Reference

| Method | Purpose |
| --- | --- |
| `get($url,$query)` / `post/put/patch/delete($url,$data)` / `head($url)` | Perform a request |
| `setHeader($n,$v)` / `setOpt($k,$v)` / `setTimeout($s)` / `setBasicAuthentication($u,$p)` | Configure |
| `response()` / `raw()` / `headers()` | Read the response |
| `status()` / `isSuccess()` | Status helpers |
| `error()` / `errorCode()` / `hasError()` | Transport errors |
| `close()` | Free the handle |

Related source: `System/Libraries/Curl.php`. Tests: `System/Test/Libraries/CurlTest.php`.
