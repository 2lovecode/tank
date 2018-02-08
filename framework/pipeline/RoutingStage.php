<?php
/**
 * User: liuhao
 * Date: 18-2-8
 * Time: 下午2:33
 */

namespace tank\pipeline;


use tank\base\Component;

class RoutingStage extends Component implements StageInterface
{
    public function handle($requestObject)
    {
        return $requestObject;
    }
}