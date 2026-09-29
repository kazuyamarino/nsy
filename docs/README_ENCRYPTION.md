# NSY Encryption Library — User Tutorial

Native encryption helper at `System/Libraries/Encryption.php` (namespace
`System\Libraries`, class `Encryption`). Replaces the former
`lablnet/encryption` dependency. All methods are **static**.

New payloads use **AES-256-GCM** (authenticated) with a fresh random 12-byte IV
per call and a versioned envelope:

```text
v1:<base64( iv[12] | tag[16] | ciphertext )>
```

## Configuration

Set a key in `env.php`:

```php
'ENCRYPTION_KEY' => 'change-this-to-a-long-random-secret',
```

The value is hashed (SHA-256) into the 32-byte cipher key. Never commit the real
key; rotate it if it leaks (see *Legacy data* below).

## Quick Start

```php
use System\Libraries\Encryption;

$cipher = Encryption::encrypt('secret message');   // 'v1:...'
$plain  = Encryption::decrypt($cipher);            // 'secret message'
```

Pass an explicit key to bypass `ENCRYPTION_KEY` (useful for tests):

```php
$cipher = Encryption::encrypt('hello', 'test-key');
Encryption::decrypt($cipher, 'test-key');
```

## Behaviour

- `decrypt()` returns `null` when the payload is invalid or was tampered with
  (GCM authentication failure) — check for `null`, it does not throw.
- `encrypt()` throws `RuntimeException` when no key is configured.
- `isEncrypted($payload)` reports whether a value is in the `v1:` format.

```php
$plain = Encryption::decrypt($cipher);
if ($plain === null) {
    // invalid or tampered payload
}
```

## Legacy Data

`decrypt()` also understands payloads produced by the old
`string_encrypt()` helper (AES-256-CBC, hard-coded key/IV), so existing data
keeps decrypting. `string_encrypt()` itself now delegates to this library and
falls back to the legacy routine when `ENCRYPTION_KEY` is not set.

> Re-encrypt legacy values with `Encryption::encrypt()` during a key rotation;
> the legacy path only exists for backward compatibility.

## Quick Reference

| Method | Purpose | Returns |
| --- | --- | --- |
| `Encryption::encrypt($plain,$key=null)` | Encrypt → `v1:` envelope | `string` |
| `Encryption::decrypt($payload,$key=null)` | Decrypt (v1 or legacy) | `string\|null` |
| `Encryption::isEncrypted($payload)` | Is it a `v1:` payload? | `bool` |
| `Encryption::key($key=null)` | Derive the 32-byte key | `string` |

Related source: `System/Libraries/Encryption.php`. Tests: `System/Test/Libraries/EncryptionTest.php`.
