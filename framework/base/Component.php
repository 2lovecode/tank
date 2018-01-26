<?php
/**
 * User: liuhao
 * Date: 18-1-18
 * Time: 下午3:08
 */

namespace tank\base;

/**
 * Class Component
 * @package tank\base
 *
 * 1.添加事件
 * 2.添加行为
 */
use Tank;

class Component extends BaseObject
{
    private $eventMap = [];

    private $behaviorMap = [];

    public function bindEvent($eventName, $eventHandler, $onData = [], $handlerSequence = -1)
    {
        $this->loadBehaviors();

        $handlerList = isset($this->eventMap[$eventName]) ? $this->eventMap[$eventName] : [];
        $insertValue = [$eventHandler, $onData];
        $this->eventMap[$eventName] = Tank::appendElementByPosition($handlerList, $handlerSequence, $insertValue);
    }

    public function getEventHandlers($eventName)
    {
        $this->loadBehaviors();

        $objectEventHandler = isset($this->eventMap[$eventName]) ? $this->eventMap[$eventName] : [];
        $classEventHandler = BaseEvent::getClassEventHandlers($this, $eventName);

        foreach ($classEventHandler as $eachClasshandler) {
            array_push($objectEventHandler, $eachClasshandler);
        }
        return $objectEventHandler;
    }

    public function unbindEvent($eventName, $eventHandler = null)
    {
        $this->loadBehaviors();

        $result = false;
        if (empty($this->eventMap[$eventName])) {
            $result = false;
        } else if (is_null($eventHandler)) {
            unset($this->eventMap[$eventName]);
            $result = true;
        } else {
            $tmpEventMap = $this->eventMap[$eventName];
            foreach ($tmpEventMap as $key => $value) {
                if ($eventHandler == $value[0]) {
                    unset($this->eventMap[$eventName][$key]);
                    $result = true;
                }
            }
            if ($result) {
                $this->eventMap[$eventName] = array_values($this->eventMap[$eventName]);
            }
        }

        return $result;
    }

    public function triggerEvent($eventName, BaseEvent $eventObject = null)
    {
        $this->loadBehaviors();

        if (!empty($this->eventMap[$eventName])) {
            if (is_null($eventObject)) {
                $eventObject = new BaseEvent();
            }
            if (is_null($eventObject->sender)) {
                $eventObject->sender = $this;
            }

            $eventObject->stopFlag = false;
            $eventObject->eventName = $eventName;

            foreach ($this->eventMap[$eventName] as $value) {
                $currentHandler = $value[0];
                $currentData = $value[1];
                $eventObject->onData = $currentData;
                call_user_func($currentHandler, $eventObject);
                if ($eventObject->stopFlag) {
                    break;
                }
            }
        }

        BaseEvent::triggerClassEvent($this, $eventName, $eventObject);
    }

    public function getBehaviors() :array
    {
        return [];
    }

    public function loadBehaviors()
    {
        if (!$this->behaviorWasLoad()) {
            foreach ($this->getBehaviors() as $behaviorName => $behaviorDefine) {
                $this->attachSingleBehavior($behaviorName, $behaviorDefine);
            }
        }
    }

    public function behaviorWasLoad()
    {
        return !empty($this->behaviorMap);
    }

    public function attachSingleBehavior($behaviorName, $behaviorDefine)
    {
        $this->loadBehaviors();

        if (!is_string($behaviorName)) {//行为必须命名,否则无法绑定
            return;
        }

        if (!($behaviorDefine instanceof BaseBehavior)) {
            $behaviorDefine = Tank::generateObject($behaviorDefine);
        }

        if (isset($this->behaviorMap[$behaviorName])) {
            $this->behaviorMap[$behaviorName]->detach();//已经存在的同名行为,先解除绑定
        }
        $behaviorDefine->attach($this);
        $this->behaviorMap[$behaviorName] = $behaviorDefine;

        return $behaviorDefine;
    }

    public function batchAttachBehavior(array $behaviors)
    {
        $this->loadBehaviors();

        foreach ($behaviors as $behaviorName => $behaviorDefine) {
            $this->attachSingleBehavior($behaviorName, $behaviorDefine);
        }
    }

    public function detachBehavior($behaviorName)
    {
        $this->loadBehaviors();

        if (isset($this->behaviorMap[$behaviorName])) {
            $behaviorObject = $this->behaviorMap[$behaviorName];
            unset($this->behaviorMap[$behaviorName]);
            $behaviorObject->detach();
            return $behaviorObject;
        } else {
            return null;
        }
    }

    public function batchDetachBehavior(array $behaviorNames)
    {
        $this->loadBehaviors();
        foreach ($behaviorNames as $eachBehaviorName) {
            $this->detachBehavior($eachBehaviorName);
        }
    }
}