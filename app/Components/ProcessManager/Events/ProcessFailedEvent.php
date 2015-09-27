<?php


namespace Servelat\Components\ProcessManager\Events;


use Symfony\Component\EventDispatcher\Event;

/**
 * Class ProcessFailedEvent.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class ProcessFailedEvent extends Event
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