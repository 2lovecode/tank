<?php
/**
 * User: liuhao
 * Date: 17-8-7
 * Time: 上午11:35
 */

namespace tank\web;

use Tank;
use tank\pipeline\RoutingPipeline;
use tank\pipeline\RoutingProcessor;

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

    public function run()
    {
        try {
            //获取请求
            $request = new WebRequest();
            //
            $routeProcessor = new RoutingProcessor();

            $routePipeline = new RoutingPipeline($routeProcessor, $request);

            $routePipeline->registerStage();

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
        
    }
}