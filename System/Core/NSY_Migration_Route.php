<?php
declare(strict_types=1);
/**
 * Please do not delete, move, change the contents of this file.
 */
use System\Core\NSY_Desk;

// Migration Route — HTTP trigger (development only). Production should use CLI: `nsy run:migrate`.
// Guarded: only registered when APP_ENV === 'development' to prevent public DDL.
if (config_app('app_env') === 'development') {
	Route::any('/migup=(:any)', function ($class) {
		NSY_Desk::mig_up($class);
	});

	Route::any('/migdown=(:any)', function ($class) {
		NSY_Desk::mig_down($class);
	});
}
