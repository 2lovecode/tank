<?php
/**
 * User: liuhao
 * Date: 18-1-30
 * Time: 下午4:49
 */

namespace tank\di;

use Tank;
use tank\base\Component;
use tank\base\Exception;

class ServiceLocator extends Component
{
    private $serviceMap = [];

    private $defineMap = [];

    public function registerService($serviceName, $serviceDefinition)
    {
        if (!empty($serviceDefinition)) {
            if ($this->hasServiceInstance($serviceName)) {
                unset($this->serviceMap[$serviceName]);
            }

            if (is_callable($serviceDefinition, true)) {
                $this->defineMap[$serviceName] = $serviceDefinition;
            } else if (is_object($serviceDefinition)) {
                $this->defineMap[$serviceName] = $serviceDefinition;
            } else if (is_array($serviceDefinition)){
                if (isset($serviceDefinition['class'])) {
                    $this->defineMap[$serviceName] = $serviceDefinition;
                } else {
                    throw new Exception('class key must be set in service definition array!');
                }
            } else {
                throw new Exception('service definition must be callable,object or array!');
            }
        } else {
            throw new Exception('service definition can not be empty!');
        }
    }

    public function removeService($serviceName)
    {
        if ($this->hasServiceInstance($serviceName)) {
            unset($this->serviceMap[$serviceName]);
        }

        if ($this->hasServiceDefine($serviceName)) {
            unset($this->defineMap[$serviceName]);
        }

        return;
    }

    public function parseService($serviceName)
    {
        $result = null;

        if (isset($this->serviceMap[$serviceName])) {
            $result = $this->serviceMap[$serviceName];
        } else if (isset($this->defineMap[$serviceName])) {
            $definition = $this->defineMap[$serviceName];

            if (is_callable($definition, true)) {
                $result = $definition;
            } else if (is_object($definition)) {
                $result = $definition;
            } else {
                $result = Tank::generateObject($definition);
            }

            $this->serviceMap[$serviceName] = $result;
        } else {
            throw new Exception('no this servcie!');
        }

        return $result;
    }


    public function hasServiceDefine($serviceName)
    {
        return isset($this->defineMap[$serviceName]);
    }

    public function hasServiceInstance($serviceName)
    {
        return isset($this->serviceMap[$serviceName]);
    }

    public function __get($name)
    {
        if ($this->hasServiceDefine($name)) {
            return $this->parseService();
        } else {
            return parent::__get($name);
        }
    }

    public function __isset($name)
    {
        if ($this->hasServiceInstance($name)) {
            return true;
        } else {
            return parent::__isset($name);
        }
    }
}