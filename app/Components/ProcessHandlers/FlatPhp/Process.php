<?php


namespace Servelat\Components\ProcessHandlers\FlatPhp;

use Servelat\Components\ProcessHandlers\ResourceBasedProcessInterface;
use Servelat\Components\ProcessManager\ProcessInterface;
use Servelat\Components\TaskManager\TaskInterface;

class Process implements ProcessInterface, ResourceBasedProcessInterface
{

    /**
     * @param \Servelat\Components\TaskManager\TaskInterface $task
     */
    public function __construct(TaskInterface $task)
    {
        // TODO: Implement __construct() method.
    }

    /**
     * Get list of streams (stdin, stdout, stderr).
     * Every stream must be a resource of type "stream".
     *
     * @return array
     */
    public function getStreams()
    {
        // TODO: Implement getStreams() method.
    }

    /**
     * Set exit code.
     * 0 - for success, any other code - for failure.
     *
     * @param int $exitCode
     * @return $this
     */
    public function setExitCode($exitCode)
    {
        // TODO: Implement setExitCode() method.
    }

    /**
     * Get exit code.
     * 0 - for success, any other code - for failure.
     *
     * @return int
     */
    public function getExitCode()
    {
        // TODO: Implement getExitCode() method.
    }

    /**
     * Add new line from stdout/stderr.
     *
     * @param $line
     * @return $this
     */
    public function addOutputLine($line)
    {
        // TODO: Implement addOutputLine() method.
    }

    /**
     * Get array of process output.
     *
     * @return array
     */
    public function getOutputLines()
    {
        // TODO: Implement getOutputLines() method.
    }

    /**
     * Get the caused task item.
     *
     * @return TaskInterface
     */
    public function getTask()
    {
        // TODO: Implement getTask() method.
    }

    /**
     * Is process closed.
     *
     * @return bool
     */
    public function isClosed()
    {
        // TODO: Implement isClosed() method.
    }

    /**
     * Return process resource.
     *
     * @return $this
     */
    public function close()
    {
        // TODO: Implement close() method.
    }

    /**
     * Set resource returned by the proc_open() function call.
     *
     * @param resource $resource
     * @return $this
     */
    public function setResource($resource)
    {
        // TODO: Implement setResource() method.
    }

    /**
     * Set streams (pipes) returned by the proc_open() function call.
     *
     * @param array $streams Array of stream resources
     * @return $this
     */
    public function setStreams(array $streams)
    {
        // TODO: Implement setStreams() method.
    }
}