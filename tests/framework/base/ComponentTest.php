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
    public $testValue = null;

    public function testBindEvent()
    {
        $component = new Component();

        $component->bindEvent('aaa', 'abc', ['a', 'b']);
        $component->bindEvent('aaa', 'ccc', ['a', 'b'], 1);

        $this->assertEquals(['aaa' => [['ccc', ['a', 'b']], ['abc', ['a', 'b']]]], $component->eventMap);
    }

    public function testTriggerEvent()
    {
        $component = new Component();
        $component->bindEvent('aaa', [$this, 'callBackFunction'], ['a']);
        $component->triggerEvent('aaa');

        $this->assertEquals(123, $this->testValue);
    }

    public function callBackFunction()
    {
        $this->testValue = 123;
    }
}