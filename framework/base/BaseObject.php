<?php
/**
 * User: liuhao
 * Date: 18-1-18
 * Time: 下午3:07
 */

namespace tank\base;

/**
 * Class Object
 * @package tank\base
 *
 * 框架所有对象的基类
 *
 * 1.提供获取当前类名的静态方法
 * 2.提供在构造时批量设置属性的功能
 * 3.提供类在构造时的初始化方法
 * 4.提供通过setter方式设置属性,getter方式获取属性的特性
 */
use Tank;

class BaseObject
{
    public static function getClassName()
    {
        return get_called_class();
    }

    public function __construct(array $config = [])
    {
        if (!empty($config)) {
            Tank::batchSetProperty($this, $config);
        }

        $this->initRun();
    }

    public function initRun()
    {

    }

    public function __set($name, $value)
    {
        $setter = 'set'.ucfirst($name);
        $getter = 'get'.ucfirst($name);

        if (method_exists($this, $setter)) {
            return $this->$setter($value);
        } else if (method_exists($this, $getter)) {
            throw new Exception('read-only property!');
        } else {
            throw new Exception('unknown property!');
        }
    }

    public function __get($name)
    {
        $setter = 'set'.ucfirst($name);
        $getter = 'get'.ucfirst($name);

        if (method_exists($this, $getter)) {
            return $this->$getter();
        } else if (method_exists($this, $setter)) {
            throw new Exception('write-only property!');
        } else {
            throw new Exception('unknown property!');
        }
    }

    public function __isset($name)
    {
        $getter = 'get'.ucfirst($name);

        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        } else {
            return false;
        }
    }

    public function __unset($name)
    {
        $setter = 'set'.ucfirst($name);
        $getter = 'get'.ucfirst($name);

        if (method_exists($this, $setter)) {
            return $this->$setter(null);
        } else if (method_exists($this, $getter)) {
            throw new Exception('read-only property!');
        }
    }

}