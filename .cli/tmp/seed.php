<?php

/**
 * NSY CLI seeder runner (no web server required).
 *
 * Usage: php .cli/tmp/seed.php <SeederClass>
 *        php .cli/tmp/seed.php all
 */

$root = dirname(__DIR__, 2);

require $root . '/System/Vendor/autoload.php';
require $root . '/System/Core/NSY_Helpers_Global.php';

new System\Core\NSY_System();
System\Core\NSY_Desk::registerSystem();

$target = $argv[1] ?? '';

if ($target === '') {
	fwrite(STDERR, "Usage: php .cli/tmp/seed.php <SeederClass|all>\n");
	exit(1);
}

if ($target === 'all') {
	$result = System\Core\NSY_Seeder::runAll();
	printf("Seeders processed (ran: %d, failed: %d)\n", $result['ran'], $result['failed']);
	exit($result['failed'] > 0 ? 1 : 0);
}

if (!System\Core\NSY_Seeder::run($target)) {
	exit(1);
}

printf("Seeded: %s\n", $target);
