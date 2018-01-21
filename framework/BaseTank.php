<?php
/**
 * User: liuhao
 * Date: 17-8-7
 * Time: 上午11:20
 */

namespace tank;

/**
 * Class BaseTank
 * @package tank
 *
 * BaseTank 作为框架最重要的类,提供一些组织框架的方法
 *
 * 属性:
 * 1.$app:      持有application对象,是一个单例
 * 2.$classMap: 注册了类的索引,数组键是类名,数组值是对应的类文件路径
 * 3.$aliasMap: 别名索引,所有注册的别名,在这里体现
 * 4.$container:DI容器,在创建对象时会使用它提供的依赖注入机制
 *
 * 方法:
 * 1.提供注册别名的方法:     registerAlias
 * 2.提供解析别名的方法:     parseAlias
 * 3.提供类的自动加载机制:   autoload
 * 4.提供创建对象的方法,基于DI:        generateObject
 * 5.提供给对象属性批量赋值的方法:      batchSetProperty
 *
 *
 */
use tank\base\ClassNotExistsException;

defined('TANK_DEBUG') || define('TANK_DEBUG', false);

class BaseTank
{
    public static $app;

    public static $classMap = [];

    public static $aliasMap = [
        '@@tank' => [
            '@@tank' =>  __DIR__
        ],
    ];

    public static $container;

    const CLASS_SEPARATOR = '\\';

    public static function registerAlias($alias, $filePath = '')
    {
        if (!empty($filePath)) {
            $dirSeparator = static::getDirSeparator();

            if (strncmp($alias, '@@', 2) !== 0) {
                $alias = '@@'.$alias;
            }

            $firstSeparatorPos = strpos($alias, $dirSeparator);

            $isMultipleLayers = $firstSeparatorPos !== false ? true : false;

            $filePathHasAlias = strncmp($filePath, '@@', 2) !== 0 ? false : true;

            $filePath = $filePathHasAlias ? static::parseAlias($filePath) : rtrim($filePath, static::CLASS_SEPARATOR.$dirSeparator);


            if ($isMultipleLayers) {
                $rootNode = substr($alias, 0, $firstSeparatorPos);
            } else {
                $rootNode = $alias;
            }

            if (!isset(static::$aliasMap[$rootNode]) || empty(static::$aliasMap[$rootNode])) {
                static::$aliasMap[$rootNode] = [
                    $alias => $filePath,
                ];
            } else if (is_array(static::$aliasMap[$rootNode])){
                static::$aliasMap[$rootNode][$alias] = $filePath;
                krsort(static::$aliasMap[$rootNode]);
            }
        }
    }

    public static function deleteAlias($alias)
    {
        $dirSeparator = static::getDirSeparator();

        if (strncmp($alias, '@@', 2) !== 0) {
            $alias = '@@'.$alias;
        }

        $firstSeparatorPos = strpos($alias, $dirSeparator);

        $isMultipleLayers = $firstSeparatorPos !== false ? true : false;

        if ($isMultipleLayers) {
            $rootNode = substr($alias, 0, $firstSeparatorPos);
        } else {
            $rootNode = $alias;
        }

        if (isset(static::$aliasMap[$rootNode])) {
            if (!empty(static::$aliasMap[$rootNode])) {
                unset(static::$aliasMap[$rootNode][$alias]);
            }
        }
    }

    public static function parseAlias($alias)
    {
        if (strncmp($alias, '@@', 2)) {
            return $alias;
        }

        $dirSeparator = static::getDirSeparator();

        $firstSeparatorPos = strpos($alias, $dirSeparator);

        $isMultipleLayers = $firstSeparatorPos !== false ? true : false;

        if ($isMultipleLayers) {
            $rootNode = substr($alias, 0, $firstSeparatorPos);
        } else {
            $rootNode = $alias;
        }

        if (isset(static::$aliasMap[$rootNode]) && !empty(static::$aliasMap[$rootNode])) {
            foreach (static::$aliasMap[$rootNode] as $eachAlias => $eachFilePath) {
                if (strpos($alias . $dirSeparator, $eachAlias . $dirSeparator) === 0) {
                    return $eachFilePath . substr($alias, strlen($eachAlias));
                }
            }
        }
    }

    public static function autoload($className)
    {
        $dirSeparator = static::getDirSeparator();
        //备注：框架类的自动加载,交由composer处理.如果不想使用composer处理,必须使用别名机制.
        if (isset(static::$classMap[$className])) {
            $classFilePath = static::$classMap[$className];
            if (strpos($classFilePath, '@@') !== false) {
                $classFilePath = static::parseAlias($classFilePath);
            }
        } else if(strpos($className, static::CLASS_SEPARATOR) !== false) {
            $classFilePath = static::parseAlias('@@' . str_replace(static::CLASS_SEPARATOR, $dirSeparator, $className) . '.php');
            if ($classFilePath === false || !is_file($classFilePath)) {
                return;
            }
        } else {
            return;
        }

        if (isset($classFilePath) && file_exists($classFilePath)) {
            include_once($classFilePath);
        }

        if (TANK_DEBUG) {
            if (!class_exists($className, false) && !interface_exists($className, false) && !trait_exists($className, false)) {
                throw new ClassNotExistsException("Class ".$className." not exists in file: $classFilePath");
            }
        }
    }

    public static function generateObject()
    {

    }

    public static function batchSetProperty()
    {

    }

    public static function getDirSeparator()
    {
        return '/';
    }

}