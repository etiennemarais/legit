<?php

class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        // Force the test database so local will still have data
        putenv('DB_CONNECTION=sqlite');
        putenv('DB_DATABASE=:memory:');

        return require __DIR__.'/../bootstrap/app.php';
    }
}
