<?php
/**
 * User: liuhao
 * Date: 18-2-8
 * Time: 下午3:20
 */

namespace tank\web;


use tank\http\BaseRequest;

class WebRequest extends BaseRequest
{
    private $_getParams = null;
    private $_postParams = null;
    private $_url = null;
    //获取get参数
    public function getParams()
    {
        if ($this->_getParams == null) {
            $this->_getParams = $_GET;
        }

        return $this->_getParams;
    }

    //获取post参数
    public function postParams()
    {
        if ($this->_postParams == null) {
            $this->_postParams = $_POST;
        }
        return $this->_postParams;
    }

    //获取url
    public function getUrl()
    {
        if ($this->_url == null) {
            $this->_url = $_SERVER['REQUEST_URI'];
        }

        return $this->_url;
    }
}