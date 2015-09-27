<?php


namespace Servelat\Components\TaskManager\Handlers\Idle;


use Servelat\Components\ProcessManager\Processes\EmptyProcess;
use Servelat\Components\ProcessManager\ProcessInterface;
use Servelat\Components\TaskManager\Events\ProcessTaskEvent;
use Servelat\Components\TaskManager\TaskHandlerInterface;
use Servelat\Components\TaskManager\TaskInterface;

/**
 * Class IdleHandler.
 * The idle handler can handle any task without performing any real action on it.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class IdleHandler implements TaskHandlerInterface
{
    /**
     * Check if the concrete handler
     * is suitable for the concrete task.
     *
     * @param \Servelat\Components\TaskManager\TaskInterface $task
     * @return boolean
     */
    public function isSuitableFor(TaskInterface $task)
    {
        return true;
    }

    /**
     * Handle the Task.
     *
     * @param \Servelat\Components\TaskManager\TaskInterface $task
     * @return ProcessInterface
     */
    public function handle(TaskInterface $task)
    {
        return new EmptyProcess($task);
    }

    /**
     * @param \Servelat\Components\TaskManager\Events\ProcessTaskEvent $event
     */
    public function onProcessTask(ProcessTaskEvent $event)
    {
        $task = $event->getTask();
        if ($this->isSuitableFor($task)) {
            if (null !== $process = $this->handle($task)) {
                $event->setProcess($process);
                $event->stopPropagation();
            }
        }
    }
}