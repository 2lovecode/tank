<?php
/**
 * User: liuhao
 * Date: 18-2-8
 * Time: 下午2:32
 */

namespace tank\pipeline;


use tank\base\Component;

class RoutingPipeline extends Component implements PipelineInterface
{
    private $requestObject;

    private $stageMap = [];

    private $processor;

    public function __construct(ProcessorInterface $processor, $requestObject)
    {
        $this->processor = $processor;
        $this->requestObject = $requestObject;

        parent::__construct($processor);
    }

    public function setProcessor(ProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    public function setPayLoad($requestObject)
    {
        $this->requestObject = $requestObject;
    }

    public function registerStage(StageInterface $stage)
    {
        $this->stageMap[] = $stage;
        return $this;
    }

    public function run()
    {
        return $this->processor->process($this->stageMap, $this->requestObject);
    }

}