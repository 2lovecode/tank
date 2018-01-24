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
class Component extends BaseObject
{
    private $eventMap = [];

    private $behaviorMap = [];

    public function bindEvent($eventName, $eventHandler, $onData = [], $handlerSequence = -1)
    {
        $handlerList = isset($this->eventMap[$eventName]) ? $this->eventMap[$eventName] : [];
        $insertValue = [$eventHandler, $onData];
        $this->eventMap[$eventName] = $this->appendElementByPosition($handlerList, $handlerSequence, $insertValue);
    }

    public function getEventMap()
    {
        return $this->eventMap;
    }

    public function appendElementByPosition(array $input, int $postion = -1, $insertValue)
    {
        $len = count($input);
        $result = [];

        if ($postion < 0) {
            $index = $len + $postion + 1;
        } else if ($postion > 0) {
            $index = $postion - 1;
        } else {
            return $input;
        }

        if ($index === $len) {
            array_push($input, $insertValue);
            $result = $input;
        } else {
            foreach ($input as $key => $value) {
                if ($key === $index) {
                    $result[] = $insertValue;
                }
                $result[] = $value;
            }
        }

        return $result;
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
    }
}