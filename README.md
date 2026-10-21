# victorio/support

Shared helpers for the Victorio Filament plugins. Free and open source (MIT).

- Requires PHP `^8.3`, Laravel `^12.0|^13.0` (`illuminate/support`, `illuminate/database`,
  `illuminate/translation`) and `ext-dom`. No Filament, no service provider.
- No user-facing text, so no translations: the exception messages are for developers.

## Database

### `ModelKey`

Morph and tenant ids as strings, so int, ULID and UUID keys can share one `string(36)` column:

```php
use Victorio\Support\Database\ModelKey;

ModelKey::of($quote);          // "42", "01J9Z…", "9b2c…"
ModelKey::stringify($key);     // the same for a raw key; anything but a scalar or Stringable throws
```

### `StringKeyedMorphMany`

A `MorphMany` for a string morph id column, whatever the parent's key type. Every parent key is
bound as a string (PostgreSQL refuses `varchar = integer`), and eager loading uses a bound
`whereIn` instead of Laravel's `whereIntegerInRaw` for int keys:

```php
use Victorio\Support\Database\StringKeyedMorphMany;

/**
 * @return StringKeyedMorphMany<PublicLink, $this>
 */
public function publicLinks(): StringKeyedMorphMany
{
    $instance = new PublicLink;
    $table = $instance->getTable();

    return new StringKeyedMorphMany($instance->newQuery(), $this, $table.'.linkable_type', $table.'.linkable_id', $this->getKeyName());
}
```

## Testing — for test suites only

The classes in `Victorio\Support\Testing` are meant for tests. Nothing loads them at runtime; they
cost nothing unless a test calls them.

### `PseudoLocale`

Finds hard-coded text: after `enable()` every translation renders as `⟦…⟧`, and `untranslated()`
lists the visible text (and `alt`, `title`, `aria-label`, `placeholder`) of a page that is
neither inside `⟦ ⟧` nor one of the allowed data strings. A translation that contains markup (a
link inside a sentence) is still recognised; a missing key stays unwrapped and is reported.

```php
use Victorio\Support\Testing\PseudoLocale;

PseudoLocale::enable();
$html = (string) $this->get('/p/'.$token)->getContent();

expect(PseudoLocale::untranslated($html, allowed: ['Quote 42', 'Acme Ltd']))->toBe([]);
```

`PseudoTranslator` is the translator it installs.

## License

MIT — see the repository. Please report vulnerabilities privately, as described in
[SECURITY.md](https://github.com/victorio-dev/support/blob/main/SECURITY.md).
