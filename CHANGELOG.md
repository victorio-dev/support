# Changelog

All notable changes to `victorio/support` are documented here. The format follows
[Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and the project uses
[semantic versioning](https://semver.org/spec/v2.0.0.html): the public API is everything not
marked `@internal`.

## [Unreleased]

### Added

- The test suite runs on sqlite (default, in memory), mysql or pgsql, selected by
  `DB_CONNECTION` and the usual `DB_*` variables; every test uses `RefreshDatabase`.

### Fixed

- The test suite did not start under `zend.assertions=-1` (production php.ini, CI): a tracked
  `phpunit.xml.dist` replaces the config Pest generated inside an `assert()` call.

## [0.2.0] - 2026-10-20

### Breaking

- Minimum PHP raised from 8.2 to 8.3, matching the version baseline in
  [ADR-0001](../docs/decisions.md).
- `illuminate/support: ^12.0|^13.0` is now a hard requirement. The package was previously
  framework-agnostic on paper.

### Added

- Composer scripts `test`, `analyse`, `format`, `format-check`.
- PHPStan level 8 (`phpstan.neon`, no baseline), Pint config, Pest + Orchestra Testbench setup.

## [0.1.0] - 2026-10-19

### Added

- Initial tag: package metadata and an empty `src/`.
