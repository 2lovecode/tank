<?php
/**
 * User: liuhao
 * Date: 18-1-24
 * Time: 下午3:24
 */

namespace tankunit\framework\base;


use tankunit\TankTestCase;

class BaseBehaviorTest extends TankTestCase
{
    public function testTemp()
    {
        $this->assertEquals('a', 'a');
    }
}