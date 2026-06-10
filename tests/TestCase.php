<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Create the application for testing.
     *
     * SAFETY: this app runs in Docker where DB_CONNECTION / DB_DATABASE are set
     * as real OS environment variables. Those override phpunit.xml's <env>
     * values (even with force="true"), so the test suite would otherwise run
     * against the live MySQL database — and every RefreshDatabase test would
     * wipe it. We pin the connection to a throwaway in-memory SQLite database
     * HERE, after bootstrap, where the environment can no longer override it.
     */
    public function createApplication(): Application
    {
        /** @var Application $app */
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'                  => 'sqlite',
            'database'                => ':memory:',
            'prefix'                  => '',
            'foreign_key_constraints' => true,
        ]);

        // Docker also sets CACHE_STORE / SESSION_DRIVER / QUEUE_CONNECTION as real
        // env vars that override phpunit.xml. Pin them to isolated, in-process
        // drivers so cache state can't leak between tests (the visitor-tracking
        // middleware relies heavily on the cache for dedup/session bookkeeping).
        $app['config']->set('cache.default', 'array');
        $app['config']->set('session.driver', 'array');
        $app['config']->set('queue.default', 'sync');
        $app['config']->set('mail.default', 'array');

        return $app;
    }
}
