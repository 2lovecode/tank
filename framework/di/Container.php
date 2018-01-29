<?php
/**
 * User: liuhao
 * Date: 18-1-18
 * Time: 下午3:02
 */

namespace tank\di;


use tank\base\Exception;

class Container
{
    private $defineMap = [];

    private $paramsMap = [];

    private $singletonMap = [];

    private $dependencyMap = [];

    private $reflectionMap = [];


    public function register($class, $define, array $params = [])
    {
        $define = $this->formatDefine($class, $define);
        $this->defineMap[$class] = $define;
        $this->paramsMap[$class] = $params;
        unset($this->singletonMap[$class]);
        return $this;
    }

    public function registerSingleton($class, $define, array $params = [])
    {
        $define = $this->formatDefine($class, $define);
        $this->defineMap[$class] = $define;
        $this->paramsMap[$class] = $params;
        $this->singletonMap[$class] = null;
        return $this;
    }

    /**
     * @param $class
     * @param $define
     * @return array|callable
     * @throws Exception
     *
     * 1.set('tank\base\Component')
     * 2.set('tank\base\AInterface', 'tank\base\ExtendsA')
     * 3.set('name', 'tank\base\Example')
     * 4.set('tank\base\AInterface', [
     *  'class' => 'tank\base\ExtendsA'
     * ])
     * 5.set('name', [
     *  'class' => 'tank\base\Example',
     *  'pro1' => 'value1',
     * ])
     * 6.set('tank\base\AInterface', function(){});
     * 7.set('name', function(){})
     * 8.set('name', $object)
     */
    public function formatDefine($class, $define)
    {
        $result = [];

        if (empty($define)) {
            $result['class'] = $class;
        } else if (is_string($define)) {
            $result[$class] = $define;
        } else if (is_callable($define, true)) {
            $result = $define;
        } else if (is_object($define)) {
            $result = $define;
        } else if (is_array($define)) {
            if (!array_key_exists('class', $define) && (strpos($class, '\\') !== false)) {
                $define['class'] = $class;
            } else {
                throw new Exception('Please provide class value!');
            }
            $result = $define;
        } else {
            throw new Exception('Define is illegal!');
        }

        return $result;
    }

    public function resolve($class, $params = [], $config = [])
    {
        $result = null;

        if (isset($this->singletonMap[$class])) {
            $result = $this->singletonMap[$class];
        } else if (!isset($this->defineMap[$class])) {
            $result = $this->generateObject();
        } else if (is_callable($this->defineMap[$class], true)) {

        } else if (is_object($this->defineMap[$class])) {
            $result = $this->defineMap[$class];
            $this->singletonMap[$class] = $this->defineMap[$class];
        } else if (is_array($this->defineMap[$class])) {

        } else {

        }

        return $result;
    }

    protected function generateObject()
    {
        return null;
    }

    protected function parseConstructorDependency()
    {}

    protected function resolveConstructorDependency()
    {}
}