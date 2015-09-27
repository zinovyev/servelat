<?php


namespace Servelat\Components\ProcessManager\Processes;

use Servelat\Components\ProcessManager\ProcessInterface;
use Servelat\Components\TaskManager\TaskInterface;

/**
 * Class EmptyProcess.
 * Empty process with zero result.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class EmptyProcess implements ProcessInterface
{
    /**
     * @var array
     */
    protected $streams;

    /**
     * @var array
     */
    protected $output;

    /**
     * @var int
     */
    protected $exitCode;

    /**
     * @var bool
     */
    protected $closed = false;

    /**
     * @var \Servelat\Components\TaskManager\TaskInterface
     */
    protected $task;

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
        if (null === $this->streams) {
            $this->streams = [
                fopen('/dev/null', 'r'),
                fopen('/dev/null', 'w'),
                fopen('/dev/null', 'w'),
            ];
        }

        return $this->streams;
    }

    /**
     * Add new line from stdout/stderr.
     *
     * @param string $line
     * @return $this
     */
    public function addOutputLine($line)
    {
        $this->output[] = (string) $line;

        return $this;
    }

    /**
     * Get array of process output.
     *
     * @return array
     */
    public function getOutputLines()
    {
        return $this->output;
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
        $this->exitCode = $exitCode;

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
     * Is process closed.
     *
     * @return bool
     */
    public function isClosed()
    {
        return $this->closed;
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
     * Get the caused task item.
     *
     * @return TaskInterface
     */
    public function getTask()
    {
        return $this->task;
    }
}