<?php
/**
 * User: liuhao
 * Date: 18-2-8
 * Time: 下午3:11
 */

namespace tank\pipeline;


use tank\base\Component;

class RoutingProcessor extends Component implements ProcessorInterface
{
    public function process($stages, $requestObject)
    {
        foreach ($stages as $eachStage) {
            $requestObject = call_user_func([$eachStage, 'handle'], $requestObject);
        }

        return $requestObject;
    }
}