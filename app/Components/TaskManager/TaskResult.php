<?php


namespace Servelat\Components\TaskManager;


use Servelat\Components\ProcessManager\Exceptions\RuntimeException;
use Servelat\Components\ProcessManager\ProcessInterface;

/**
 * Class TaskResult.
 * Task result wrapper.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class TaskResult
{
    /**
     * @var \Servelat\Components\TaskManager\TaskInterface
     */
    protected $task;

    /**
     * @var array
     */
    protected $outputLines;

    /**
     * @var int
     */
    protected $exitCode;

    /**
     * @param \Servelat\Components\ProcessManager\ProcessInterface $process
     */
    public function __construct(ProcessInterface $process)
    {
        if (!$process->isClosed()) {
            new RuntimeException('The process must be closed!');
        }

        $this->task = $process->getTask();
        $this->outputLines = $process->getOutputLines();
        $this->exitCode = $process->getExitCode();
    }

    /**
     * @return TaskInterface
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @return array
     */
    public function getOutputLines()
    {
        return $this->outputLines;
    }

    /**
     * @return int
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }
}