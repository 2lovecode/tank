<?php
/**
 * User: liuhao
 * Date: 18-1-30
 * Time: 下午4:49
 */

namespace tank\di;


use tank\base\Component;

class ServiceLocator extends Component
{
    private $serviceMap = [];

    private $defineMap = [];

    public function registerService()
    {}

    public function parseService()
    {}


}