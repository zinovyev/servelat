<?php


namespace Servelat\Components\ProcessHandlers\FlatPhp;

use Servelat\Components\ProcessHandlers\ResourceBasedProcessInterface;
use Servelat\Components\ProcessManager\ProcessInterface;
use Servelat\Components\TaskManager\TaskInterface;

/**
 * Class Process.
 * Simple wrapper for the process opened with proc_open() function.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class Process implements ProcessInterface, ResourceBasedProcessInterface
{
    /**
     * @var \Servelat\Components\TaskManager\TaskInterface
     */
    protected $task;

    /**
     * @var array
     */
    protected $streams;

    /**
     * @var integer
     */
    protected $exitCode = -1;

    /**
     * @var array
     */
    protected $outputLines = [];

    /**
     * @var bool
     */
    protected $closed = false;

    /**
     * @var resource
     */
    protected $resource;

    /**
     * @param \Servelat\Components\TaskManager\TaskInterface $task
     */
    public function __construct(TaskInterface $task)
    {
        $this->task = $task;
    }

    /**
     * Get list of streams (stdin, stdout, stderr).
     * Every stream must be a resource of type "stream".
     *
     * @return array
     */
    public function getStreams()
    {
        return $this->streams;
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
        $this->exitCode = (int) $exitCode;

        return $this;
    }

    /**
     * Get exit code.
     * 0 - for success, any other code - for failure.
     *
     * @return int
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }

    /**
     * Add new line from stdout/stderr.
     *
     * @param $line
     * @return $this
     */
    public function addOutputLine($line)
    {
        $this->outputLines[] = $line;

        return $this;
    }

    /**
     * Get array of process output.
     *
     * @return array
     */
    public function getOutputLines()
    {
        return $this->outputLines;
    }

    /**
     * Get the caused task item.
     *
     * @return TaskInterface
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Is process closed.
     *
     * @return bool
     */
    public function isClosed()
    {
        return !!$this->closed;
    }

    /**
     * Return process resource.
     *
     * @return $this
     */
    public function close()
    {
        $this->closed = true;

        return $this;
    }

    /**
     * Set resource returned by the proc_open() function call.
     *
     * @param resource $resource
     * @return $this
     */
    public function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Set streams (pipes) returned by the proc_open() function call.
     *
     * @param array $streams Array of stream resources
     * @return $this
     */
    public function setStreams(array $streams)
    {
        $this->streams = $streams;

        return $this;
    }
}