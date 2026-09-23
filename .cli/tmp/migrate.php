<?php

/**
 * NSY CLI migration runner (no web server required).
 *
 * Usage: php .cli/tmp/migrate.php <MigrationClass> [up|down]
 */

$root = dirname(__DIR__, 2);

require $root . '/System/Vendor/autoload.php';
require $root . '/System/Core/NSY_Helpers_Global.php';

new System\Core\NSY_System();
System\Core\NSY_Desk::register_system();

$class = $argv[1] ?? '';
$direction = strtolower($argv[2] ?? 'up');

if ($class === '') {
	fwrite(STDERR, "Usage: php .cli/tmp/migrate.php <MigrationClass> [up|down]\n");
	exit(1);
}

if (!in_array($direction, ['up', 'down'], true)) {
	fwrite(STDERR, "Direction must be 'up' or 'down'\n");
	exit(1);
}

// mig_up()/mig_down() exit() after running, so one class per process.
if ($direction === 'down') {
	System\Core\NSY_Desk::mig_down($class);
} else {
	System\Core\NSY_Desk::mig_up($class);
}
