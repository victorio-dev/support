<?php

declare(strict_types=1);

namespace Victorio\Support\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $notable_type
 * @property string $notable_id
 * @property string $body
 */
final class Note extends Model
{
    protected $table = 'fixture_notes';

    protected $guarded = [];
}
