<?php

namespace System\Middlewares;

/**
 * Custom middleware scaffold.
 *
 * NOTE: NSY core security is handled by SecurityMiddleware (CSRF, sanitize, XSS).
 * There is no automatic middleware pipeline — invoke this explicitly, e.g. from
 * a route closure or controller:
 *
 *   $mw = new middleware_tmp();
 *   $mw->handle(function () {
 *       return Route::goto([YourController::class, 'method']);
 *   });
 *
 * For built-in CSRF/XSS use SecurityMiddleware; for rate limiting use
 * Route::createSecurityMiddleware('strict')->rateLimit('<bucket>').
 */
class middleware_tmp
{
	/**
	 * @param callable $next
	 * @return mixed
	 */
	public function handle(callable $next): mixed
	{
		// Logic before the request is handled
		$result = $next();
		// Logic after the request is handled

		return $result;
	}
}
