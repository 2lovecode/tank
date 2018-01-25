<?php
/**
 * User: liuhao
 * Date: 18-1-24
 * Time: 下午2:35
 */

namespace tank\base;


class BaseBehavior extends BaseObject
{
    public $subject;

    public function getEvents()
    {
        return [];
    }

    public function attach(Component $subject)
    {
        $this->subject = $subject;
        foreach ($this->getEvents() as $eventName => $eventHandler) {
            $eventHandler = is_string($eventHandler) ? [$this, $eventHandler] : $eventHandler;
            $this->subject->bindEvent($eventName, $eventHandler);
        }
    }

    public function detach()
    {
        if ($this->subject) {
            foreach ($this->getEvents() as $eventName => $eventHandler) {
                $eventHandler = is_string($eventHandler) ? [$this, $eventHandler] : $eventHandler;
                $this->subject->unbindEvent($eventName, $eventHandler);
            }
            $this->subject = null;
        }

    }


}