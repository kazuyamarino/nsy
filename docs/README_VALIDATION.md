# NSY Validation Library — User Tutorial

Rule-based validation uses the retained third-party library **`rakit/validation`**
(MIT, Laravel-inspired), exposed to the framework through the alias
`System\Libraries\Validator` → `Rakit\Validation\Validator`
(see `System/Config/App.php`).

> `System\Libraries\Validate` is a **sanitizer / type caster**
> (`asString()`, `asEmail()`, …) — not a rule validator. Use `Validator` for
> rules such as `required|email|min:6`, and `Validate` to clean values.
> See [Libraries](README_LIBRARIES.md) for the sanitizer.

## Table of Contents

1. [Quick Start](#quick-start)
2. [Common Rules](#common-rules)
3. [Custom Messages](#custom-messages)
4. [Reading the Result](#reading-the-result)
5. [Combining with Request](#combining-with-request)
6. [Quick Reference](#quick-reference)

## Quick Start

```php
use System\Libraries\Validator;   // alias of Rakit\Validation\Validator

$validator  = new Validator();
$validation = $validator->make($data, [
    'name'     => 'required|min:3',
    'email'    => 'required|email',
    'password' => 'required|min:6',
    'confirm'  => 'required|same:password',
]);

$validation->validate();

if ($validation->fails()) {
    $errors = $validation->errors()->firstOfAll();   // ['email' => 'The Email is not valid', ...]
} else {
    $clean = $validation->getValidatedData();        // only the validated keys
}
```

`validate($inputs, $rules, $messages)` is a shortcut that returns the same
`Validation` object:

```php
$validation = $validator->validate($data, ['email' => 'required|email']);
```

## Common Rules

| Rule | Meaning |
| --- | --- |
| `required` | present and not empty |
| `nullable` | skip other rules when the value is null/empty |
| `email` / `url` / `ip` | format checks |
| `numeric` / `integer` | number checks |
| `min:n` / `max:n` | length (string/array) or value (number) |
| `between:a,b` | value/length in range |
| `in:a,b,c` / `not_in:a,b` | membership |
| `same:field` / `different:field` | compare with another field |
| `regex:/.../` | pattern |
| `date` / `after:...` / `before:...` | date checks |
| `array` / `uploaded_file` / `mimes:png,jpg` | array and file checks |

Array / nested keys use dot + wildcard notation:

```php
$validation = $validator->make($data, [
    'skills'            => 'array',
    'skills.*.id'       => 'required|numeric',
    'skills.*.percent'  => 'required|numeric|between:0,100',
]);
```

> The full rule list lives in the library — see `System/Vendor/rakit/validation`
> or the upstream project (`github.com/rakit/validation`).

## Custom Messages

Pass a third argument keyed by `field` **or** `field:rule`:

```php
$validation = $validator->make($data, [
    'email' => 'required|email',
], [
    'email.required' => 'Alamat e-mail wajib diisi.',
    'email.email'    => 'Format e-mail tidak valid.',
    'required'       => 'Kolom :attribute wajib diisi.',
]);
```

Register a reusable custom rule with `addValidator()`:

```php
$validator->addValidator('uppercase', new class extends \Rakit\Validation\Rule {
    protected $message = ':attribute harus huruf besar';
    public function check($value): bool { return strtoupper((string) $value) === (string) $value; }
});
```

## Reading the Result

```php
$validation->fails();                       // bool
$validation->errors()->firstOfAll();        // ['field' => 'message', ...]
$validation->errors()->first('email');      // first message for one field
$validation->getValidatedData();            // only keys that passed
```

## Combining with Request

`Request` sanitizes input into a clean array first, then `Validator` applies rules:

```php
use System\Libraries\Request;
use System\Libraries\Validator;

$input = Request::input('POST')->asArray([
    'name'  => 'string',
    'email' => 'string',
]);

$validation = (new Validator())->validate($input, [
    'name'  => 'required|min:3',
    'email' => 'required|email',
]);
```

## Quick Reference

| API | Purpose |
| --- | --- |
| `new Validator()` | Create a validator (aliased class) |
| `->make($data, $rules, $messages=[])` | Build a `Validation` |
| `->validate($data, $rules, $messages=[])` | Shortcut |
| `$validation->validate()` | Run the rules |
| `$validation->fails()` | Did it fail? |
| `$validation->errors()->firstOfAll()` | All error messages |
| `$validation->errors()->first($field)` | One field's message |
| `$validation->getValidatedData()` | Validated keys only |
| `$validator->addValidator($name, $rule)` | Custom rule |

Related source: alias in `System/Config/App.php`; library at `System/Vendor/rakit/validation`.
