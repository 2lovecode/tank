<?php
/**
 * Created by PhpStorm.
 * User: liuhao
 * Date: 17-8-8
 * Time: 上午11:40
 */

namespace tank\web;



class UrlManager
{
    private $urlFlag = 'r';

    public $defaultModule = 'app';
    public $defaultController = 'welcome';
    public $defaultAction = 'index';
    public $delimiter = '/';

    /**
     * Name: parseUrl
     * Desc: 把请求对象传进来,解析路由,
     * User: LiuHao<liu546hao@163.com>
     * Date:
     */
    public function parseUrl(Request $request)
    {
        $module = $this->defaultModule;
        $controller = $this->defaultController;
        $action = $this->defaultAction;
        $ctrlParams = $request->getParams();
        if (!empty($ctrlParams) && isset($ctrlParams[$this->urlFlag])) {
            $array = explode($this->delimiter, $ctrlParams[$this->urlFlag]);
            switch (count($array)) {
                case 0:
                    break;
                case 1:
                    $controller = $array[0];
                    break;
                case 2:
                    $controller = $array[0];
                    $action = $array[1];
                    break;
                case 3:
                default:
                    $module = $array[0];
                    $controller = $array[1];
                    $action = $array[2];
                    break;
            }
        }
        return [$module, $controller, $action];
    }
}