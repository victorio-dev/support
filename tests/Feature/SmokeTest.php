<?php

declare(strict_types=1);

it('boots a test application', function (): void {
    expect(app()->version())->toBeString()
        ->and(app()->environment())->toBe('testing');
});

it('declares the supported version baseline', function (): void {
    $raw = (string) file_get_contents(__DIR__.'/../../composer.json');

    /** @var array{license: string, require: array<string, string>} $composer */
    $composer = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);

    expect($composer['license'])->toBe('MIT')
        ->and($composer['require']['php'])->toBe('^8.3')
        ->and($composer['require']['illuminate/support'])->toBe('^12.0|^13.0')
        ->and($composer['require']['illuminate/database'])->toBe('^12.0|^13.0')
        ->and($composer['require']['illuminate/translation'])->toBe('^12.0|^13.0');
});

it('autoloads the package namespace', function (): void {
    $raw = (string) file_get_contents(__DIR__.'/../../composer.json');

    /** @var array{autoload: array{'psr-4': array<string, string>}} $composer */
    $composer = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);

    expect($composer['autoload']['psr-4'])->toHaveKey('Victorio\Support\\')
        ->and(is_dir(__DIR__.'/../../src'))->toBeTrue();
});
