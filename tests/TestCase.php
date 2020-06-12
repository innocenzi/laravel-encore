<?php

namespace Innocenzi\LaravelEncore\Tests;

use Innocenzi\LaravelEncore\EncoreServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [EncoreServiceProvider::class];
    }
}
