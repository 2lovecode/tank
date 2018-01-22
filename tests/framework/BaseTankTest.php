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
        $beforeArray = Tank::$aliasMap;

        Tank::registerAlias('@@a', 'p1/p2');
        Tank::registerAlias('@@a/b', 'p2/p3');
        Tank::registerAlias('@@c', 'p4/p5');
        Tank::registerAlias('@@c/d/f', 'p6/p7');
        Tank::registerAlias('@@d', '@@c/p6/p7');
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
        $this->assertEquals($afterArray, Tank::$aliasMap);
    }

    public function testParseAlias()
    {
        Tank::registerAlias('@@c', 'p1/p2');
        Tank::registerAlias('@@c/d', 'p2/p3');

        $this->assertEquals('p1/p2/aa', Tank::parseAlias('@@c/aa'));
        $this->assertEquals('p2/p3/aa', Tank::parseAlias('@@c/d/aa'));
    }
}