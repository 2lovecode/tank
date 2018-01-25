<?php
/**
 * User: liuhao
 * Date: 18-1-24
 * Time: 上午10:53
 */

namespace tankunit\framework\base;


use tankunit\TankTestCase;
use tank\base\Component;

class ComponentTest extends TankTestCase
{
    public function testBindEvent()
    {
        $component = new Component();

        $component->bindEvent('aaa', 'abc', ['a', 'b']);
        $component->bindEvent('aaa', 'ccc', ['a', 'b'], 1);
        $expect = [
            [
                'ccc',
                [
                    'a',
                    'b'
                ]
            ],
            [
                'abc',
                [
                    'a',
                    'b'
                ]
            ]
        ];

        $this->assertEquals($expect, $component->getEventHandlers('aaa'));
    }
}