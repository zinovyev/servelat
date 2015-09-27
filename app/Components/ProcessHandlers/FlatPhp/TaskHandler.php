<?php


namespace Servelat\Components\ProcessHandlers\FlatPhp;


use Servelat\Components\ProcessManager\ProcessInterface;
use Servelat\Components\TaskManager\TaskHandlerInterface;
use Servelat\Components\TaskManager\TaskInterface;

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
}