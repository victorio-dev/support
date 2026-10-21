<?php

declare(strict_types=1);

namespace Victorio\Support\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Victorio\Support\Database\StringKeyedMorphMany;

/**
 * @mixin Model
 */
trait HasNotes
{
    /**
     * @return StringKeyedMorphMany<Note, $this>
     */
    public function notes(): StringKeyedMorphMany
    {
        $instance = new Note;

        return new StringKeyedMorphMany($instance->newQuery(), $this, 'fixture_notes.notable_type', 'fixture_notes.notable_id', $this->getKeyName());
    }
}
