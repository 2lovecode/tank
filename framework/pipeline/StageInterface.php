<?php
/**
 * User: liuhao
 * Date: 18-2-8
 * Time: 下午1:59
 */

namespace tank\pipeline;


interface StageInterface
{
    public function handle($payLoad);
}