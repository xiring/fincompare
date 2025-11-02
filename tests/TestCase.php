<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Database\Eloquent\Factories\Factory;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Database\\Factories\\'.class_basename($modelName).'Factory';
        });
    }
}
