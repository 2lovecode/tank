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
        $handlerList = isset($this->eventMap[$eventName]) ? $this->eventMap[$eventName] : [];
        $insertValue = [$eventHandler, $onData];
        $this->eventMap[$eventName] = Tank::appendElementByPosition($handlerList, $handlerSequence, $insertValue);
    }

    public function getEventHandlers($eventName)
    {
        $objectEventHandler = isset($this->eventMap[$eventName]) ? $this->eventMap[$eventName] : [];
        $classEventHandler = BaseEvent::getClassEventHandlers($this, $eventName);

        foreach ($classEventHandler as $eachClasshandler) {
            array_push($objectEventHandler, $eachClasshandler);
        }
        return $objectEventHandler;
    }

    public function unbindEvent($eventName, $eventHandler = null)
    {
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
}