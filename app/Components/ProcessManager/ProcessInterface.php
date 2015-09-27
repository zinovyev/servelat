<?php


namespace Servelat\Components\ProcessManager;

use Servelat\Components\TaskManager\TaskInterface;

/**
 * Interface ProcessInterface.
 * Base process interface.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface ProcessInterface
{
    /**
     * @param \Servelat\Components\TaskManager\TaskInterface $task
     */
    public function __construct(TaskInterface $task);

    /**
     * Get list of streams (stdin, stdout, stderr).
     * Every stream must be a resource of type "stream".
     *
     * @return array
     */
    public function getStreams();

    /**
     * Set exit code.
     * 0 - for success, any other code - for failure.
     *
     * @param int $exitCode
     * @return $this
     */
    public function setExitCode($exitCode);

    /**
     * Get exit code.
     * 0 - for success, any other code - for failure.
     *
     * @return int
     */
    public function getExitCode();

    /**
     * Add new line from stdout/stderr.
     *
     * @param $line
     * @return $this
     */
    public function addOutputLine($line);

    /**
     * Get array of process output.
     *
     * @return array
     */
    public function getOutputLines();

    /**
     * Get the caused task item.
     *
     * @return TaskInterface
     */
    public function getTask();

    /**
     * Is process closed.
     *
     * @return bool
     */
    public function isClosed();

    /**
     * Return process resource.
     *
     * @return $this
     */
    public function close();
}