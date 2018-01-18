<?php
/**
 * Created by PhpStorm.
 * User: liuhao
 * Date: 17-8-7
 * Time: 上午11:20
 */

namespace tank;


class BaseTank
{
    public static $app;

    public static $classMap = [];

    /**
     * Name: autoload
     * Desc: 类的自动加载机制
     * User: LiuHao<liu546hao@163.com>
     * Date: 2017-08-07
     * @param
     */
    public static function autoload($className)
    {
        self::setClassMap();
        $realName = self::getClassMap($className);

        if (file_exists($realName)) {
            include($realName);
        }
    }

    /**
     * Name: getClassMap
     * Desc:
     * User: LiuHao<liu546hao@163.com>
     * Date:
     * @param
     * @return string
     */
    public static function getClassMap($className)
    {
        $realName = '';
        $classStr = trim($className, '\\');
        $location = strpos($classStr, '\\');
        $prefix = substr($classStr, 0, $location);
        $suffix = substr($classStr, $location);
        $classMap = self::$classMap;
        if (isset($classMap[$prefix])) {
            $realName = $classMap[$prefix].implode('/', explode('\\', trim($suffix, '\\'))).'.php';
        }

        return $realName;

    }

    /**
     * Name: setClassMap
     * Desc: 设置类的map
     * User: LiuHao<liu546hao@163.com>
     * Date:
     */
    public static function setClassMap(array $map = null)
    {
        self::$classMap['root'] = '../';
        self::$classMap['tank'] = '../framework/tank/tank-core/';
    }
}