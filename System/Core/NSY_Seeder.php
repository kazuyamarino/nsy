<?php

declare(strict_types=1);

namespace System\Core;

use System\Libraries\Log\LogManager;

/**
 * Runner for database seeders (System/Seeders).
 *
 * A seeder is any class in System\Seeders with a public run() method. This
 * class resolves the class, runs it and reports the outcome. It is CLI-first
 * (see `nsy run:seed`), but can also be called from application code.
 *
 * See docs/README_SEEDER.md.
 */
class NSY_Seeder
{
	/**
	 * Directory that holds the seeder classes.
	 *
	 * @return string
	 */
	public static function directory(): string
	{
		return dirname(__DIR__) . '/Seeders';
	}

	/**
	 * List seeder class names (file basenames), sorted. The abstract base
	 * class Seeder is skipped.
	 *
	 * @return array<int,string>
	 */
	public static function names(): array
	{
		$dir = self::directory();
		if (!is_dir($dir)) {
			return [];
		}

		$names = [];
		foreach (glob($dir . '/*.php') ?: [] as $file) {
			$name = basename($file, '.php');
			if ($name === 'Seeder') {
				continue;
			}
			$names[] = $name;
		}

		sort($names);

		return $names;
	}

	/**
	 * Run a single seeder class by name.
	 *
	 * @param  string $name
	 * @return bool  True on success
	 */
	public static function run(string $name): bool
	{
		$classname = 'System\\Seeders\\' . $name;

		if (!class_exists($classname)) {
			echo "Seeder class not found: {$classname}\n";
			return false;
		}

		$seeder = new $classname();

		if (!method_exists($seeder, 'run')) {
			echo "Seeder {$name} has no run() method\n";
			return false;
		}

		try {
			$seeder->run();
		} catch (\Throwable $e) {
			echo "Seeder failed: {$name} — {$e->getMessage()}\n";
			return false;
		}

		try {
			LogManager::channel('seeder')->info('Seeder executed', ['class' => $classname]);
		} catch (\Throwable $e) {
			// never let logging break seeding
		}

		return true;
	}

	/**
	 * Run every seeder, in name order.
	 *
	 * @return array{ran:int,failed:int}
	 */
	public static function runAll(): array
	{
		$ran = 0;
		$failed = 0;

		foreach (self::names() as $name) {
			if (self::run($name)) {
				$ran++;
			} else {
				$failed++;
			}
		}

		return ['ran' => $ran, 'failed' => $failed];
	}
}
