<?php

declare(strict_types=1);

namespace Victorio\Support\Database;

use Illuminate\Database\Eloquent\Model;
use Stringable;
use UnexpectedValueException;

/**
 * Morph and tenant ids as strings, so int, ULID and UUID keys share one `string(36)` column.
 * Every id a Victorio package writes into such a column or binds against it goes through here.
 *
 *     ModelKey::of($quote);          // "42", "01J…", "9b2c…"
 *     ModelKey::stringify($key);     // the same for a raw key value
 */
final class ModelKey
{
    public static function of(Model $model): string
    {
        return self::stringify($model->getKey());
    }

    /**
     * Keys arrive as `mixed` from Eloquent; only scalars and Stringables are valid ids.
     *
     * @throws UnexpectedValueException for anything else (an array, an object, null)
     */
    public static function stringify(mixed $key): string
    {
        if (is_scalar($key) || $key instanceof Stringable) {
            return (string) $key;
        }

        throw new UnexpectedValueException('A model key must be a scalar value.');
    }
}
