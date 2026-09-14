<?php
/**
 * Please do not delete, move, change the contents of this file.
 */
use System\Core\NSY_Desk;

// Migration Route
Route::any('/migup=(:any)', function ($class) {
	NSY_Desk::mig_up($class);
});

Route::any('/migdown=(:any)', function ($class) {
	NSY_Desk::mig_down($class);
});
