<?php

declare(strict_types=1);

/**
 * PHP-CS-Fixer — NSY ENFORCED ruleset (conservative gate).
 *
 * NSY is indented with TABS (see .editorconfig), so the indent is configured
 * explicitly instead of PSR-12's four spaces.
 *
 * This ruleset only contains fixers the current codebase already satisfies, so
 * `composer lint` is a clean, zero-churn gate that keeps new code consistent.
 *
 *   composer lint       # check (dry run, no changes)  <- used by CI
 *   composer lint:fix   # apply the enforced rules
 *   composer lint:full  # apply full PSR-12 + tabs (one-time normalisation)
 *
 * To adopt full PSR-12 formatting, run `composer lint:full` in a dedicated
 * commit, then move the rules from .php-cs-fixer.full.php into this file.
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
		// Whitespace / structure
		'encoding' => true,
		'full_opening_tag' => true,
		'no_closing_tag' => true,
		'no_multiple_statements_per_line' => true,
		'no_singleline_whitespace_before_semicolons' => true,
		'no_empty_statement' => true,
		'no_unneeded_braces' => true,
		'no_useless_concat_operator' => true,
		'no_useless_return' => true,
		'blank_line_after_namespace' => true,
		'blank_line_between_import_groups' => true,
		'single_blank_line_at_eof' => true,

		// Casing / namespaces
		'lowercase_keywords' => true,
		'lowercase_static_reference' => true,
		'magic_constant_casing' => true,
		'native_function_casing' => true,
		'native_type_declaration_casing' => true,
		'clean_namespace' => true,
		'no_leading_import_slash' => true,
		'no_unneeded_import_alias' => true,

		// Types / casts
		'short_scalar_cast' => true,
		'return_type_declaration' => true,
		'types_spaces' => true,

		// Operators / access
		'standardize_not_equals' => true,
		'normalize_index_brace' => true,
		'object_operator_without_whitespace' => true,
		'combine_consecutive_issets' => true,
		'combine_consecutive_unsets' => true,

		// PHPDoc
		'phpdoc_types' => true,
		'phpdoc_var_without_name' => true,
		'phpdoc_indent' => true,
		'phpdoc_single_line_var_spacing' => true,
		'phpdoc_trim_consecutive_blank_line_separation' => true,
	]);
