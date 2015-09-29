<?php


namespace Servelat\Components\TaskManager;

use Servelat\Components\TaskManager\Events\AfterProcessTaskEvent;
use Servelat\Components\TaskManager\Events\ProcessTaskEvent;
use Servelat\ServelatEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class TaskManager.
 * Servelat task manager.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class TaskManager
{
    /**
     * @var \SplQueue
     */
    protected $taskQueue;

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    public function __construct(\SplQueue $taskQueue, EventDispatcher $dispatcher)
    {
        $this->taskQueue = $taskQueue;
        $this->dispatcher = $dispatcher;
    }

    public function onMessageReceived()
    {

    }

    public function onProcessClosed()
    {

    }

    /**
     * Push the task to the queue.
     *
     * @param \Servelat\Components\TaskManager\TaskInterface $task
     * @return $this
     */
    public function addTask(TaskInterface $task)
    {
        $this->taskQueue->push($task);

        return $this;
    }

    /**
     * Count tasks left in the queue.
     *
     * @return int
     */
    public function countTasks()
    {
        return $this->taskQueue->count();
    }

    /**
     * Try to process next task.
     *
     * @return null|\Servelat\Components\ProcessManager\ProcessInterface
     */
    public function handleNext()
    {
        try {
            /** @var TaskInterface $task */
            $task = $this->taskQueue->shift();
        } catch (\RuntimeException $ex) {
            return null;
        }

        // Handle task
        $processTaskEvent = new ProcessTaskEvent($task);
        $this->dispatcher->dispatch(
            ServelatEvents::TASK_MANAGER_PROCESS_TASK,
            $processTaskEvent
        );

        if (null === $process = $processTaskEvent->getProcess()) {
            throw new \RuntimeException('The task was not handled correctly.');
        }

        $afterProcessTaskEvent = new AfterProcessTaskEvent($process);
        $this->dispatcher->dispatch(
            ServelatEvents::TASK_MANAGER_AFTER_PROCESS_TASK,
            $afterProcessTaskEvent
        );

        return $process;
    }
}