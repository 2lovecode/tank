<?php
/**
 * User: liuhao
 * Date: 18-1-30
 * Time: 下午4:06
 */

namespace tank\di;


class Instance
{
    public $id;

    protected function __construct($id)
    {
        $this->id = $id;
    }

    public static function generate($id)
    {
        return new static($id);
    }
}