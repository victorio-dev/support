Global rules: ../CLAUDE.md

# victorio/support

Shared helpers for every Victorio package (MIT, public Packagist). Depends on Laravel components
only — never on Filament or another Victorio package (global dependency rule).

## Public API

Everything in `src/` is public API (semver):

- `Database\ModelKey`, `Database\StringKeyedMorphMany` — string morph and tenant ids.
- `Testing\PseudoLocale`, `Testing\PseudoTranslator` — for test suites only; never loaded at
  runtime.

## Conventions specific to this package

- No user-facing text, so no `resources/lang` (rule D7 applies as soon as there is some). The
  exception messages are for developers.
- A query or relation change runs `make check-db PKG=support` (F7).

## Commands

```bash
make check PKG=support      # git-check + F1-F3
make check-db PKG=support   # sqlite, mysql, pgsql
```
