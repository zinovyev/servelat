<?php


namespace Servelat\Components\TaskManager\Events;


use Servelat\Components\ProcessManager\ProcessInterface;
use Servelat\Components\TaskManager\TaskInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class TaskManagerHandleTaskEvent.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class ProcessTaskEvent extends Event
{
    /**
     * @var TaskInterface
     */
    protected $task;

    /**
     * @var ProcessInterface
     */
    protected $process;

    /**
     * @param \Servelat\Components\TaskManager\TaskInterface $task
     */
    public function __construct(TaskInterface $task)
    {
        $this->task = $task;
    }

    /**
     * @return \Servelat\Components\TaskManager\TaskInterface
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @return ProcessInterface
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @param ProcessInterface $process
     * @return TaskManagerHandleTaskEvent
     */
    public function setProcess(ProcessInterface $process)
    {
        $this->process = $process;

        return $this;
    }
}