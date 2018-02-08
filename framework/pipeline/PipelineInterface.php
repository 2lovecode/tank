<?php
/**
 * User: liuhao
 * Date: 18-2-8
 * Time: 下午1:57
 */

namespace tank\pipeline;


interface PipelineInterface
{
    public function __construct(ProcessorInterface $processor, $payLoad);

    public function setProcessor(ProcessorInterface $processor);

    public function setPayLoad($payLoad);

    public function registerStage(StageInterface $stage);

    public function run();
}