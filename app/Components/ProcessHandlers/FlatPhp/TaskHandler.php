<?php


namespace Servelat\Components\ProcessHandlers\FlatPhp;


use Servelat\Components\ProcessManager\ProcessInterface;
use Servelat\Components\TaskManager\Events\ProcessTaskEvent;
use Servelat\Components\TaskManager\TaskHandlerInterface;
use Servelat\Components\TaskManager\TaskInterface;

/**
 * Class TaskHandler.
 * The handler for the flat-php task.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class TaskHandler implements TaskHandlerInterface
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
        return $task instanceof Task;
    }

    /**
     * Handle the Task.
     * Execute the command from the task and return the process instance.
     *
     * @param \Servelat\Components\TaskManager\TaskInterface $task
     * @return ProcessInterface
     */
    public function handle(TaskInterface $task)
    {
        // Build command
        $cmd = sprintf(
            'exec %s -r "%s" &',
            PHP_BINARY,
            stripslashes($task->getPayload())
        );

        // Descriptors
        $descriptors = [
            ['pipe', 'r'], // Process stdin
            ['pipe', 'w'], // Process stdout
            ['pipe', 'w'], // Process stderr
        ];

        // Execute cmd
        $resource = proc_open(
            $cmd,
            $descriptors,
            $streams
        );

        $process = new Process($task);
        $process->setResource($resource);
        $process->setStreams($streams);

        return $process;
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