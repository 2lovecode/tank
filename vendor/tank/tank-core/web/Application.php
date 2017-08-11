<?php
/**
 * Created by PhpStorm.
 * User: liuhao
 * Date: 17-8-7
 * Time: 上午11:35
 */

namespace tank\web;

use Tank;

class Application
{
    public function __construct(array $config)
    {
        Tank::$app = $this;
        $this->setConfig($config);
    }


    public function setConfig(array $config)
    {
        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Name: run
     * Desc: 接收请求,处理数据,返回结果
     * User: LiuHao<liu546hao@163.com>
     * Date:
     */
    public function run()
    {
        try {
            //获取请求
            $request = new Request();
            //路由解析
            $urlManager = new UrlManager();
            list($module, $controller, $action) = $urlManager->parseUrl($request);
            //逻辑处理
            $object = $this->createController($module, $controller);

            $result = call_user_func([$object, $action]);
            //返回响应

            return $result;
        } catch (\Exception $e) {
            var_dump($e->getMessage());
        }
    }

    public function createController($module, $controller)
    {
        $className = 'root\modules\\'.$module.'\controllers\\'.ucfirst($controller).'Controller';
        if (class_exists($className)) {
            $object = new $className();
            return $object;
        } else {
            throw new \Exception('Controller '.ucfirst($controller).'Controller is not exists!');
        }
    }
}