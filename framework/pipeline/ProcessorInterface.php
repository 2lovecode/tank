<?php
/**
 * User: liuhao
 * Date: 18-2-8
 * Time: 下午3:00
 */

namespace tank\pipeline;


interface ProcessorInterface
{
    public function process($stages, $payLoad);
}