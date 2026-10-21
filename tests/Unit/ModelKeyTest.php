<?php

declare(strict_types=1);

use Victorio\Support\Database\ModelKey;
use Victorio\Support\Tests\Fixtures\IntParent;

it('turns scalar and stringable keys into strings', function (mixed $key, string $expected): void {
    expect(ModelKey::stringify($key))->toBe($expected);
})->with([
    'an int' => [42, '42'],
    'a ULID' => ['01J9Z3ZQ6Q3V3V3V3V3V3V3V3V', '01J9Z3ZQ6Q3V3V3V3V3V3V3V3V'],
    'a Stringable' => [str('9b2c'), '9b2c'],
]);

it('refuses a key that is not a scalar', function (mixed $key): void {
    expect(fn (): string => ModelKey::stringify($key))->toThrow(UnexpectedValueException::class);
})->with([
    'null' => [null],
    'an array' => [[1]],
    'an object' => [new stdClass],
]);

it('reads the key of a model', function (): void {
    $parent = IntParent::create();

    expect(ModelKey::of($parent))->toBe((string) $parent->getKey());
});
