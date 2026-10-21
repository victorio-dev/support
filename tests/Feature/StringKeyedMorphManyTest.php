<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Victorio\Support\Tests\Fixtures\IntParent;
use Victorio\Support\Tests\Fixtures\Note;
use Victorio\Support\Tests\Fixtures\UlidParent;
use Victorio\Support\Tests\Fixtures\UuidParent;

dataset('parents', [
    'int key' => [IntParent::class],
    'ULID key' => [UlidParent::class],
    'UUID key' => [UuidParent::class],
]);

it('creates and reads related rows for every key type, on every database', function (string $parent): void {
    /** @var IntParent|UlidParent|UuidParent $owner */
    $owner = $parent::create();
    $other = $parent::create();

    $owner->notes()->create(['body' => 'first']);
    $owner->notes()->create(['body' => 'second']);
    $other->notes()->create(['body' => 'other']);

    expect($owner->notes()->orderBy('id')->pluck('body')->all())->toBe(['first', 'second'])
        ->and(Note::query()->where('body', 'first')->sole()->notable_id)->toBe((string) $owner->getKey());
})->with('parents');

it('binds the parent key as a string', function (string $parent): void {
    $owner = $parent::create();

    $queries = [];
    DB::listen(function ($query) use (&$queries): void {
        $queries[] = $query->bindings;
    });

    $owner->notes()->get();

    expect($queries)->not->toBe([])
        ->and(array_filter($queries[0], is_int(...)))->toBe([]);
})->with('parents');

it('eager loads with a bound whereIn of strings', function (string $parent): void {
    $owners = [$parent::create(), $parent::create()];
    $owners[0]->notes()->create(['body' => 'a']);
    $owners[1]->notes()->create(['body' => 'b']);

    $sql = [];
    DB::listen(function ($query) use (&$sql): void {
        $sql[] = [$query->sql, $query->bindings];
    });

    $loaded = $parent::query()->with('notes')->orderBy('created_at')->get();
    $eager = collect($sql)->first(fn (array $query): bool => str_contains($query[0], 'fixture_notes'))
        ?? throw new LogicException('The relation was not eager loaded.');

    expect($loaded->sum(fn ($owner): int => $owner->notes->count()))->toBe(2)
        ->and($eager[0])->toContain(' in (')
        ->and(array_filter($eager[1], is_int(...)))->toBe([]);
})->with('parents');

it('has no parent key for an unsaved model', function (): void {
    expect((new IntParent)->notes()->getParentKey())->toBeNull();
});
