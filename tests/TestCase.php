<?php

declare(strict_types=1);

namespace Victorio\Support\Tests;

use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * The $app parameter stays untyped because the parent declares it untyped;
     * adding a native type here would break the signature.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        // victorio/support ships helpers only — no service provider yet.
        return [];
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function defineEnvironment($app): void
    {
        // DB_CONNECTION=mysql|pgsql (`make test DB=...`, CI) keeps Testbench's own connection of
        // that name, whose config file reads DB_HOST, DB_DATABASE and friends. Without it
        // Testbench defaults to its `testing` sqlite connection (foreign keys off), which is
        // replaced below by an in-memory one with foreign keys on.
        if (! in_array($app['config']->get('database.default'), ['sqlite', 'testing'], true)) {
            return;
        }

        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            // Enforced like on mysql/pgsql, so cascading deletes behave the same on every driver.
            'foreign_key_constraints' => true,
        ]);
    }
}
