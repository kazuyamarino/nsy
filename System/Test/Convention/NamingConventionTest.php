<?php

declare(strict_types=1);

namespace System\Test\Convention;

use PHPUnit\Framework\TestCase;

/**
 * Enforces the NSY naming conventions across System/ (third-party code in
 * System/Vendor is skipped):
 *
 *   - class methods        -> camelCase
 *   - global functions     -> snake_case
 *
 * The check is token based, so names inside strings or comments never count.
 * It runs as part of the normal PHPUnit suite, which makes it a hard CI gate.
 */
final class NamingConventionTest extends TestCase
{
	public function testClassMethodsUseCamelCase(): void
	{
		$violations = array_filter(
			self::declarations('method'),
			static fn (array $d): bool => preg_match('/^_{0,2}[a-z][a-zA-Z0-9]*$/', $d['name']) !== 1
		);

		$this->assertSame([], self::describe($violations), 'Class methods must be camelCase.');
	}

	public function testGlobalFunctionsUseSnakeCase(): void
	{
		$violations = array_filter(
			self::declarations('function'),
			static fn (array $d): bool => preg_match('/^[a-z][a-z0-9]*(?:_[a-z0-9]+)*$/', $d['name']) !== 1
		);

		$this->assertSame([], self::describe($violations), 'Global functions must be snake_case.');
	}

	/**
	 * @param  string $kind "method" or "function"
	 * @return array<int,array{name:string,file:string,line:int}>
	 */
	private static function declarations(string $kind): array
	{
		$root = dirname(__DIR__, 2); // .../System

		$files = [];
		$iterator = new \RecursiveIteratorIterator(
			new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS)
		);

		foreach ($iterator as $file) {
			if (!$file->isFile() || $file->getExtension() !== 'php') {
				continue;
			}

			$path = $file->getPathname();
			if (str_contains($path, DIRECTORY_SEPARATOR . 'Vendor' . DIRECTORY_SEPARATOR)) {
				continue;
			}
			if (str_contains($path, DIRECTORY_SEPARATOR . 'Storage' . DIRECTORY_SEPARATOR)) {
				continue;
			}

			$files[] = $path;
		}

		sort($files);

		$found = [];
		foreach ($files as $file) {
			foreach (self::scan($file) as $declaration) {
				if ($declaration['kind'] === $kind) {
					$found[] = $declaration;
				}
			}
		}

		return $found;
	}

	/**
	 * @return array<int,array{name:string,file:string,line:int,kind:string}>
	 */
	private static function scan(string $file): array
	{
		$tokens = token_get_all((string) file_get_contents($file));
		$count  = count($tokens);
		$stack  = [];
		$pendingClass = false;
		$found  = [];

		for ($i = 0; $i < $count; $i++) {
			$token = $tokens[$i];

			if (is_array($token)) {
				$id = $token[0];

				// String interpolation braces must keep the brace balance so the
				// closing "}" never pops a real block/class frame.
				if ($id === T_CURLY_OPEN || $id === T_DOLLAR_OPEN_CURLY_BRACES) {
					$stack[] = 'block';
					continue;
				}

				if ($id === T_CLASS || $id === T_INTERFACE || $id === T_TRAIT || (defined('T_ENUM') && $id === T_ENUM)) {
					// Ignore `Foo::class`.
					if ($id === T_CLASS && self::precededBy($tokens, $i, T_DOUBLE_COLON)) {
						continue;
					}
					$pendingClass = true;
					continue;
				}

				if ($id === T_FUNCTION) {
					// Ignore `use function ...;` imports.
					if (self::precededBy($tokens, $i, T_USE)) {
						continue;
					}

					$nameIndex = self::nextSignificant($tokens, $i + 1);
					if ($nameIndex !== null && is_array($tokens[$nameIndex]) && $tokens[$nameIndex][0] === T_STRING) {
						$found[] = [
							'name' => $tokens[$nameIndex][1],
							'file' => $file,
							'line' => $tokens[$nameIndex][2],
							'kind' => in_array('class', $stack, true) ? 'method' : 'function',
						];
					}
					continue;
				}

				continue;
			}

			if ($token === '{') {
				$stack[] = $pendingClass ? 'class' : 'block';
				$pendingClass = false;
			} elseif ($token === '}') {
				if ($stack !== []) {
					array_pop($stack);
				}
			} elseif ($token === ';') {
				$pendingClass = false;
			}
		}

		return $found;
	}

	/**
	 * Index of the next token that is neither whitespace, comment nor the "&"
	 * of a by-reference declaration; null when nothing relevant follows.
	 */
	private static function nextSignificant(array $tokens, int $from): ?int
	{
		$count = count($tokens);

		for ($i = $from; $i < $count; $i++) {
			$token = $tokens[$i];

			if (is_array($token) && in_array($token[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) {
				continue;
			}
			if ($token === '&') {
				continue;
			}

			return $i;
		}

		return null;
	}

	/** True when the first significant token before $from is of type $id. */
	private static function precededBy(array $tokens, int $from, int $id): bool
	{
		for ($i = $from - 1; $i >= 0; $i--) {
			$token = $tokens[$i];

			if (is_array($token) && in_array($token[0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) {
				continue;
			}

			return is_array($token) && $token[0] === $id;
		}

		return false;
	}

	/**
	 * @param  array<int,array{name:string,file:string,line:int}> $violations
	 * @return string[]
	 */
	private static function describe(array $violations): array
	{
		return array_map(
			static fn (array $d): string => sprintf('%s:%d  %s()', $d['file'], $d['line'], $d['name']),
			array_values($violations)
		);
	}
}
