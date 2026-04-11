<?php

namespace Tests;

use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $compiledViewPath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'qtmv'.DIRECTORY_SEPARATOR.uniqid();

        File::ensureDirectoryExists($compiledViewPath);

        config()->set('view.compiled', $compiledViewPath);
    }
}
