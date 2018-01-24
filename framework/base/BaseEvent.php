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
 */
class BaseEvent extends BaseObject
{
    public $eventName;

    public $sender;

    public $onData = [];

    public $stopFlag = false;
}