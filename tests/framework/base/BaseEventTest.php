<?php
/**
 * User: liuhao
 * Date: 18-1-24
 * Time: 下午3:25
 */

namespace tankunit\framework\base;


use tankunit\TankTestCase;

class BaseEventTest extends TankTestCase
{
    public function testTemp()
    {
        $this->assertEquals('a', 'a');
    }
}