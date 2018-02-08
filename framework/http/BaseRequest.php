<?php
/**
 * User: liuhao
 * Date: 18-2-8
 * Time: 下午3:18
 */

namespace tank\http;


use tank\base\Component;
use tank\base\Exception;

class BaseRequest extends Component
{
    private $_scriptFile;

    // 通过 $_SERVER['SCRIPT_FILENAME'] 来获取入口脚本名
    public function getScriptFile()
    {
        if ($this->_scriptFile === null) {
            if (isset($_SERVER['SCRIPT_FILENAME'])) {
                $this->setScriptFile($_SERVER['SCRIPT_FILENAME']);
            } else {
                throw new Exception('Unable to determine the entry script file path.');
            }
        }

        return $this->_scriptFile;
    }

    // scriptFile属性的setter函数
    public function setScriptFile($value)
    {
        $scriptFile = realpath($value);
        if ($scriptFile !== false && is_file($scriptFile)) {
            $this->_scriptFile = $scriptFile;
        } else {
            throw new Exception('Unable to determine the entry script file path.');
        }
    }

    // 返回当前请求的方法，请留意方法名称是大小写敏感的，按规范应转换为大写字母
    public function getMethod()
    {
        if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
            return strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);

            // 或者使用 $_SERVER['REQUEST_METHOD'] 作为方法名，未指定时，默认为 GET 方法
        } else {
            return isset($_SERVER['REQUEST_METHOD']) ?
                strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';
        }
    }

    public function getUrl()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            return $_SERVER['REQUEST_URI'];
        }
    }
}