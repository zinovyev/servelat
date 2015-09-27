<?php


namespace Servelat\Components\TaskManager\Events;


use Servelat\Components\ProcessManager\ProcessInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class AfterProcessTaskEvent.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class AfterProcessTaskEvent extends Event
{
    /**
     * @var \Servelat\Components\ProcessManager\ProcessInterface
     */
    protected $process;

    /**
     * @param \Servelat\Components\ProcessManager\ProcessInterface $process
     */
    public function __construct(ProcessInterface $process)
    {
        $this->process = $process;
    }

    /**
     * @return \Servelat\Components\ProcessManager\ProcessInterface
     */
    public function getProcess()
    {
        return $this->process;
    }
}