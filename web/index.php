<?php
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
     * @param $className
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
     * @param $className
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
     * @param
     * @param array|null $map
     */
    public static function setClassMap(array $map = null)
    {
        self::$classMap['root'] = '../';
        self::$classMap['tank'] = '../vendor/tank/tank-core/';
    }
}

class Tank extends \tank\BaseTank
{
}

spl_autoload_register(['Tank', 'autoload'], true, true);


$config = [
    'mds' => 'Hello God!',
];
require(__DIR__ . '/../vendor/autoload.php');

(new tank\web\Application($config))->run();