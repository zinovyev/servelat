<?php


namespace Servelat\Components\ProcessManager\Events;

use Servelat\Components\ProcessManager\ProcessInterface;
use Servelat\Components\TaskManager\TaskInterface;

/**
 * Class ProcessOutputEvent.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class ProcessOutputEvent
{
    /**
     * @var TaskInterface
     */
    protected $task;

    /**
     * @var string
     */
    protected $output;

    /**
     * @param \Servelat\Components\ProcessManager\ProcessInterface $process
     * @param string $output
     */
    public function __construct(ProcessInterface $process, $output)
    {
        $this->task = $process->getTask();
        $this->output = (string) $output;
    }

    /**
     * @return \Servelat\Components\ProcessManager\ProcessInterface
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }
}