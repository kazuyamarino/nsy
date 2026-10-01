<?php
declare(strict_types=1);
/**
 * Please do not delete, move, change the contents of this file.
 */
use System\Core\NSY_Desk;

// Migration Route — HTTP trigger, OFF by default.
//
// The canonical way to run migrations is the CLI: `nsy run:migrate`.
// This HTTP trigger is a development convenience that must be explicitly
// enabled, because on an exposed server it would let anyone run DDL.
//
// It registers only when ALL of these hold:
//   - app_env === 'development'                    (never in production)
//   - APP_MIGRATION_HTTP=true                      (explicit opt-in)
//   - APP_MIGRATION_HTTP_TOKEN is non-empty        (required secret)
// The caller must pass the token as ?token=… (compared with hash_equals).
$migrationToken = (string) (config_env('APP_MIGRATION_HTTP_TOKEN') ?? '');
$httpMigrationsEnabled = config_app('app_env') === 'development'
	&& filter_var(config_env('APP_MIGRATION_HTTP') ?? false, FILTER_VALIDATE_BOOLEAN);

if ($httpMigrationsEnabled && $migrationToken !== '') {
	$migrationGuard = static function () use ($migrationToken): void {
		if (!hash_equals($migrationToken, (string) ($_GET['token'] ?? ''))) {
			NSY_Desk::staticErrorHandler('Migration HTTP token missing or invalid.', 403);
		}
	};

	Route::any('/migup=(:any)', function ($class) use ($migrationGuard) {
		$migrationGuard();
		NSY_Desk::migUp((string) $class);
	});

	Route::any('/migdown=(:any)', function ($class) use ($migrationGuard) {
		$migrationGuard();
		NSY_Desk::migDown((string) $class);
	});
}
