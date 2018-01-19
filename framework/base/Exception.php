<?php
/**
 * User: liuhao
 * Date: 18-1-19
 * Time: 上午10:37
 */

namespace tank\base;


class Exception extends \Exception
{
    public function getName()
    {
        return 'Exception';
    }
}