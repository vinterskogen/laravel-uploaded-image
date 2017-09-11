<?php

namespace Vinterskogen\UploadedImage\Tests;

use Mockery;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    /**
     * Tear down.
     *
     * @return void
     */
    public function tearDown()
    {
        Mockery::close();

        parent::tearDown();
    }
}
