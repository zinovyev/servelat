<?php


namespace Servelat\Components\TaskManager;


use Servelat\Components\ProcessManager\ProcessInterface;

/**
 * Interface TaskHandlerInterface.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface TaskHandlerInterface
{
    /**
     * Check if the concrete handler
     * is suitable for the concrete task.
     *
     * @param \Servelat\Components\TaskManager\TaskInterface $task
     * @return boolean
     */
    public function isSuitableFor(TaskInterface $task);

    /**
     * Handle the Task.
     *
     * @param \Servelat\Components\TaskManager\TaskInterface $task
     * @return ProcessInterface
     */
    public function handle(TaskInterface $task);
}