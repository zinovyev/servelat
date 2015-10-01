<?php


namespace Servelat\Components\TaskManager\Events;


use Servelat\Components\TaskManager\TaskResult;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ReturnTaskResultEvent.
 * This event is triggered when the task result is compiled.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class ReturnTaskResultEvent extends Event
{
    /**
     * @var \Servelat\Components\TaskManager\TaskResult
     */
    protected $taskResult;

    /**
     * @param \Servelat\Components\TaskManager\TaskResult $taskResult
     */
    public function __construct(TaskResult $taskResult)
    {
        $this->taskResult = $taskResult;
    }

    /**
     * @return TaskResult
     */
    public function getTaskResult()
    {
        return $this->taskResult;
    }
}