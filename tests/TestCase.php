<?php

namespace Hisoft\Conventions\Tests;

use Hisoft\Conventions\ConventionsServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [ConventionsServiceProvider::class];
    }
}
