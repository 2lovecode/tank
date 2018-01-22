<?php
/**
 * User: liuhao
 * Date: 18-1-19
 * Time: 下午3:59
 */
namespace tankunit\framework;

use tankunit\TankTestCase;
use tank\BaseTank;

class BaseTankTest extends TankTestCase
{
    public function testRegisterAlias()
    {
        $beforeArray = BaseTank::$aliasMap;

        BaseTank::registerAlias('@@a', 'p1/p2');
        BaseTank::registerAlias('@@a/b', 'p2/p3');
        BaseTank::registerAlias('@@c', 'p4/p5');
        BaseTank::registerAlias('@@c/d/f', 'p6/p7');
        BaseTank::registerAlias('@@d', '@@c/p6/p7');
        $afterArray = [
            '@@a' => [
                '@@a/b' => 'p2/p3',
                '@@a' => 'p1/p2',
            ],
            '@@c' => [
                '@@c/d/f' => 'p6/p7',
                '@@c' => 'p4/p5',
            ],
            '@@d' => [
                '@@d' => 'p4/p5/p6/p7',
            ]
        ];

        $afterArray = array_merge($beforeArray, $afterArray);
        $this->assertEquals($afterArray, BaseTank::$aliasMap);
    }

    public function testParseAlias()
    {
        BaseTank::registerAlias('@@c', 'p1/p2');
        BaseTank::registerAlias('@@c/d', 'p2/p3');

        $this->assertEquals('p1/p2/aa', BaseTank::parseAlias('@@c/aa'));
        $this->assertEquals('p2/p3/aa', BaseTank::parseAlias('@@c/d/aa'));
    }
}