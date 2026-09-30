<?php

declare(strict_types=1);

/**
 * PHP-CS-Fixer — NSY FULL ruleset (PSR-12 + tabs).
 *
 * This normalises the whole System/ tree (indentation to tabs + PSR-12 style).
 * It is intentionally NOT the default so the codebase is not reformatted
 * silently; run it deliberately:
 *
 *   composer lint:full
 *
 * See .php-cs-fixer.dist.php for the enforced gate and the migration note.
 */

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
	->in(__DIR__ . '/System')
	->exclude([
		'Vendor',
		'Storage',
		'Apps/Templates/razr_cache',
	])
	->name('*.php')
	->ignoreDotFiles(true)
	->ignoreVCS(true);

return (new Config())
	->setRiskyAllowed(false)
	->setUsingCache(false)
	->setIndent("\t")
	->setLineEnding("\n")
	->setFinder($finder)
	->setRules([
		'@PSR12' => true,
		'array_syntax' => ['syntax' => 'short'],
		'no_unused_imports' => true,
		'ordered_imports' => ['sort_algorithm' => 'alpha'],
		'single_quote' => true,
		'trailing_comma_in_multiline' => ['elements' => ['arrays']],
	]);
