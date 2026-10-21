<?php

declare(strict_types=1);

namespace Victorio\Support\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 */
final class IntParent extends Model
{
    use HasNotes;

    protected $table = 'fixture_int_parents';

    protected $guarded = [];
}
