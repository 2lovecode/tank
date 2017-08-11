<?php
/**
 * Created by PhpStorm.
 * User: liuhao
 * Date: 17-8-9
 * Time: 下午3:11
 */

namespace root\tests;

use PHPUnit\Framework\TestCase;
use root\modules\app\controllers\TestController;

class Test extends TestCase
{
    public function testCanBeCreatedFromValidEmailAddress()
    {
        $this->assertInstanceOf(
            TestController::class,
            TestController::fromString('user@example.com')
        );
    }

    public function testCannotBeCreatedFromInvalidEmailAddress()
    {
        $this->expectException(\Exception::class);

        TestController::fromString('invalid');
    }

    public function testCanBeUsedAsString()
    {
        $this->assertEquals(
            'user@example.com',
            TestController::fromString('user@example.com')
        );
    }
}