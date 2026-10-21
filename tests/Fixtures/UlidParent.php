<?php

declare(strict_types=1);

namespace Victorio\Support\Tests\Fixtures;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 */
final class UlidParent extends Model
{
    use HasNotes;
    use HasUlids;

    protected $table = 'fixture_ulid_parents';

    protected $guarded = [];
}
