<?php
/**
 * User: liuhao
 * Date: 18-1-24
 * Time: 下午2:35
 */

namespace tank\base;

/**
 * Class BaseEvent
 * @package tank\base
 *
 * 1.$eventName持有事件名
 * 2.$sender是发布此事件的类的实例
 * 3.$onData是在绑定事件时传递的数据
 * 4.stopFlay是事件处理器是否终止的标识,如果置为true,则之后的事件处理器不再执行
 *
 * 如果要实现对象级别的事件,以上就足以应付,之下是类级别事件的实现需要的.
 *
 *  1.$classEventMap,保存类级别事件的数据结构
 *  2.bindClassEvent,绑定事件方法
 *  3.getClassEventHandlers,获取某事件处理器的方法
 *  4.unbindClassEvent,解除绑定事件方法
 *  5.triggerClassEvent,触发事件方法
 */
use Tank;

class BaseEvent extends BaseObject
{
    public $eventName;

    public $sender;

    public $onData = [];

    public $stopFlag = false;

    private static $classEventMap = [];

    public static function bindClassEvent($className, $eventName, $eventHandler, $onData=[], $handlerSequence = -1)
    {
        $className   = ltrim($className, '\\');
        $isEmpty     = isset(self::$classEventMap[$eventName]) && isset(self::$classEventMap[$eventName][$className]) ? false : true;
        $handlerList = $isEmpty ? [] : self::$classEventMap[$eventName][$className];
        $insertValue = [$eventHandler, $onData];
        self::$classEventMap[$eventName][$className] = Tank::appendElementByPosition($handlerList, $handlerSequence, $insertValue);
    }

    public static function getClassEventHandlers($className, $eventName)
    {
        if (is_object($className)) {
            $className = get_class($className);
        } else {
            $className = ltrim($className, '\\');
        }

        $classNames = array_merge(
            [$className],
            class_parents($className, true),
            class_implements($className, true)
        );

        $handlerList = [];

        foreach ($classNames as $eachClassName) {
            $isEmpty     = isset(self::$classEventMap[$eachClassName]) && isset(self::$classEventMap[$eventName][$eachClassName]) ? false : true;
            $eachHandlerList = $isEmpty ? [] : self::$classEventMap[$eventName][$eachClassName];
            foreach ($eachHandlerList as $eachHandler) {
                array_push($handlerList, $eachHandler);
            }
        }

        return $handlerList;
    }

    public static function unbindClassEvent($className, $eventName, $eventHandler = null)
    {
        $result = false;
        $className = ltrim($className, '\\');

        if (empty(self::$classEventMap[$eventName]) || empty(self::$classEventMap[$eventName][$className])) {
            $result = false;
        } else if (is_null($eventHandler)) {
            unset(self::$classEventMap[$eventName][$className]);
            $result = true;
        } else {
            $tmpEventMap = self::$classEventMap[$eventName][$className];
            foreach ($tmpEventMap as $key => $value) {
                if ($eventHandler == $value[0]) {
                    unset(self::$classEventMap[$eventName][$className][$key]);
                    $result = true;
                }
            }
            if ($result) {
                self::$classEventMap[$eventName][$className] = array_values(self::$classEventMap[$eventName][$className]);
            }
        }

        return $result;
    }

    public static function triggerClassEvent($className, $eventName, $eventObject = null)
    {
        if (!empty(self::$classEventMap[$eventName])) {
            if (is_null($eventObject)) {
                $eventObject = new BaseEvent();
            }
            if (is_null($eventObject->sender)) {

                if (is_object($className)) {
                    $eventObject->sender = $className;
                    $className = get_class($className);
                } else {
                    $className = ltrim($className, '\\');
                }
            }

            $eventObject->stopFlag  = false;
            $eventObject->eventName = $eventName;

            $classNames = array_merge(
                [$className],
                class_parents($className, true),
                class_implements($className, true)
            );

            foreach ($classNames as $eachClassName) {
                if (!empty(self::$classEventMap[$eventName][$eachClassName])) {
                    foreach (self::$classEventMap[$eventName][$eachClassName] as $value) {
                        $currentHandler = $value[0];
                        $currentData = $value[1];
                        $eventObject->onData = $currentData;
                        call_user_func($currentHandler, $eventObject);
                        if ($eventObject->stopFlag) {
                            break 2;
                        }
                    }
                }
            }
        }
    }
}