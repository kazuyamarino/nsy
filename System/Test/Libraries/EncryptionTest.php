<?php

declare(strict_types=1);

namespace System\Test\Libraries;

use PHPUnit\Framework\TestCase;
use System\Libraries\Encryption;

class EncryptionTest extends TestCase
{
	private string $key = 'unit-test-key';

	public function testRoundTrip(): void
	{
		$cipher = Encryption::encrypt('hello world', $this->key);

		$this->assertStringStartsWith('v1:', $cipher);
		$this->assertSame('hello world', Encryption::decrypt($cipher, $this->key));
	}

	public function testRandomIvProducesDifferentCiphertext(): void
	{
		$this->assertNotSame(
			Encryption::encrypt('same plaintext', $this->key),
			Encryption::encrypt('same plaintext', $this->key)
		);
	}

	public function testTamperedPayloadReturnsNull(): void
	{
		$this->assertNull(Encryption::decrypt('v1:' . base64_encode(random_bytes(40)), $this->key));
	}

	public function testLegacyCbcPayloadDecrypts(): void
	{
		$legacyKey = hash('sha256', 'Kazu#Key!');
		$legacyIv  = substr(hash('sha256', '!VI@_$3'), 0, 16);
		$legacy    = base64_encode(openssl_encrypt('legacy-secret', 'aes-256-cbc', $legacyKey, 0, $legacyIv));

		$this->assertSame('legacy-secret', Encryption::decrypt($legacy, $this->key));
	}

	public function testEmptyKeyThrows(): void
	{
		$this->expectException(\RuntimeException::class);
		Encryption::encrypt('x', '');
	}
}
