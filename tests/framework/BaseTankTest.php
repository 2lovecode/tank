<?php
/**
 * User: liuhao
 * Date: 18-1-19
 * Time: 下午3:59
 */
namespace tankunit\framework;

use tankunit\TankTestCase;
use Tank;

class BaseTankTest extends TankTestCase
{
    public function testRegisterAlias()
    {
        print_r(Tank::$aliasMap);
    }
}