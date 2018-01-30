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
            $result['class'] = $define;
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
            $result = $this->generateObject($class, $params, $config);
        } else if (is_callable($this->defineMap[$class], true)) {
            $defineParams = isset($this->paramsMap[$class]) ? $this->paramsMap[$class] : [];
            $currentParams = array_merge($defineParams, $params);


            $define = $this->defineMap[$class];
            $result = call_user_func($define, $this, $currentParams, $config);

        } else if (is_object($this->defineMap[$class])) {
            $result = $this->defineMap[$class];
            $this->singletonMap[$class] = $this->defineMap[$class];
        } else if (is_array($this->defineMap[$class])) {
            $define = $this->defineMap[$class];
            $concrete = $define['class'];
            unset($define['class']);

            $config = array_merge($define, $config);
            $defineParams = isset($this->paramsMap[$class]) ? $this->paramsMap[$class] : [];
            $currentParams = array_merge($defineParams, $params);

            if ($concrete === $class) {
                $result = $this->generateObject($class, $currentParams, $config);
            } else {
                $result = $this->resolve($concrete, $currentParams, $config);
            }
        } else {
            throw new Exception('Error');
        }

        if (array_key_exists($class, $this->singletonMap) && is_object($result)) {
            $this->singletonMap[$class] = $result;
        }
        return $result;
    }

    protected function generateObject($class, array $params = [], array $config = [])
    {
        list ($reflection, $dependencies) = $this->parseConstructorDependency($class);

        foreach ($params as $index => $param) {
            $dependencies[$index] = $param;
        }

        $dependencies = $this->resolveConstructorDependency($dependencies, $reflection);
        if (empty($config)) {
            return $reflection->newInstanceArgs($dependencies);
        }

        if (!empty($dependencies) && is_a($class, 'tank\base\BaseObject', true)) {
            $dependencies[count($dependencies) - 1] = $config;
            return $reflection->newInstanceArgs($dependencies);
        } else {
            $object = $reflection->newInstanceArgs($dependencies);
            foreach ($config as $name => $value) {
                $object->$name = $value;
            }
            return $object;
        }
    }

    protected function parseConstructorDependency($class)
    {
        if (isset($this->reflectionMap[$class])) {
            return [$this->reflectionMap[$class], $this->dependencyMap[$class]];
        }

        $dependencies = [];
        $reflection = new \ReflectionClass($class);

        $constructor = $reflection->getConstructor();
        if ($constructor !== null) {
            foreach ($constructor->getParameters() as $param) {
                if ($param->isDefaultValueAvailable()) {
                    $dependencies[] = $param->getDefaultValue();
                } else {
                    $dependClass = $param->getClass();
                    $isClass = !is_null($dependClass) ? true : false;
                    $dependencies[] = Instance::generate($isClass ? $dependClass->getName() : null);
                }
            }
        }

        $this->reflectionMap[$class] = $reflection;
        $this->dependencyMap[$class] = $dependencies;

        return [$reflection, $dependencies];
    }

    protected function resolveConstructorDependency($dependencies, $reflection = null)
    {
        foreach ($dependencies as $index => $dependency) {
            if ($dependency instanceof Instance) {
                if (!is_null($dependency->id)) {
                    $dependencies[$index] = $this->resolve($dependency->id);
                } elseif (!is_null($reflection)) {
                    $name = $reflection->getConstructor()->getParameters()[$index]->getName();
                    $class = $reflection->getName();
                    throw new Exception($class." missing required parameter ".$name);
                }
            }
        }
        return $dependencies;
    }
}