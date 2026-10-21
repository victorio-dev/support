<?php

declare(strict_types=1);

namespace Victorio\Support\Tests\Fixtures;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $id
 */
final class UuidParent extends Model
{
    use HasNotes;
    use HasUuids;

    protected $table = 'fixture_uuid_parents';

    protected $guarded = [];
}
