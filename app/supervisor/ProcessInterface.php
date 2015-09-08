<?php

namespace servelat\supervisor;

/**
 * Interface ProcessInterface.
 * Base process interface.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface ProcessInterface
{
    /**
     * @param \servelat\supervisor\TaskInterface $task
     */
    public function __construct(TaskInterface $task);

    /**
     * Get original task.
     * Get the task object the process belongs to.
     *
     * @return \servelat\supervisor\TaskInterface
     */
    public function getTask();

    /**
     * Is still running.
     *
     * @return boolean
     */
    public function isRunning();
}