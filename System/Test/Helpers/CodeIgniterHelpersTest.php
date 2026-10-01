<?php

declare(strict_types=1);

namespace System\Test\Helpers;

use PHPUnit\Framework\TestCase;

/**
 * Regression tests for the CodeIgniter-ported global helpers.
 *
 * The helpers are loaded explicitly (System/Test/bootstrap.php) rather than by
 * Composer's eager "files" autoload — see NSY_SystemLoader::loadHelpers().
 */
final class CodeIgniterHelpersTest extends TestCase
{
	public function testHelpersAreLoaded(): void
	{
		$this->assertTrue(function_exists('entities_to_ascii'));
		$this->assertTrue(function_exists('ascii_to_entities'));
		$this->assertTrue(function_exists('url_title'));
	}

	public function testAsciiToEntitiesConvertsHighAscii(): void
	{
		$this->assertSame('caf&#233;', ascii_to_entities('café'));
	}

	public function testEntitiesToAsciiDecodesFourByteSequence(): void
	{
		// Regression: the decoder used to stop at 3 bytes, mangling emoji.
		$this->assertSame('😀', entities_to_ascii('&#128512;'));
	}

	public function testEntitiesToAsciiDecodesTwoAndThreeByteSequences(): void
	{
		$this->assertSame('café', entities_to_ascii('caf&#233;'));
		$this->assertSame('€', entities_to_ascii('&#8364;'));
	}

	public function testEntitiesToAsciiRoundTripsMultibyteText(): void
	{
		$original = 'Halo 😀 café €';

		$this->assertSame($original, entities_to_ascii(ascii_to_entities($original)));
	}

	public function testEntitiesToAsciiDecodesNamedEntities(): void
	{
		$this->assertSame('&<-', entities_to_ascii('&amp;&lt;&#45;'));
	}

	public function testUrlTitleProducesSlug(): void
	{
		$this->assertSame('halo-dunia', url_title('Halo Dunia!', '-', true));
	}

	public function testWordWrapBreaksLongText(): void
	{
		$this->assertStringContainsString("\n", word_wrap('one two three four five six', 10));
	}

	public function testAlternatorCyclesValues(): void
	{
		alternator(); // reset the internal counter
		$this->assertSame('a', alternator('a', 'b'));
		$this->assertSame('b', alternator('a', 'b'));
		$this->assertSame('a', alternator('a', 'b'));
	}

	public function testRandomStringCryptoAcceptsOddLength(): void
	{
		$token = random_string('crypto', 7);

		$this->assertSame(7, strlen($token));
		$this->assertMatchesRegularExpression('/^[0-9a-f]{7}$/', $token);
	}

	public function testRandomStringCryptoHonoursLength(): void
	{
		$this->assertSame(32, strlen(random_string('crypto', 32)));
	}
}
