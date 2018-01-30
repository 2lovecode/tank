<?php
/**
 * User: liuhao
 * Date: 18-1-29
 * Time: 下午4:52
 */

namespace tankunit\framework\di;

use tankunit\framework\BaseTankTest;

class ContainerTest extends BaseTankTest
{
    public function testTemp()
    {
        $this->assertEquals('a', 'b');
    }
}