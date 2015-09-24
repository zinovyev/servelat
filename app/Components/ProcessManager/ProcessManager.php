<?php


namespace Servelat\Components\ProcessManager;


class ProcessManager
{
    /**
     * @var \SplQueue
     */
    protected $processQueue;

    /**
     * @var array
     */
    protected $streams;

    /**
     * @param \SplQueue $processQueue
     */
    public function __construct(\SplQueue $processQueue)
    {
        $this->processQueue = $processQueue;
    }

    /**
     * Add new process to the queue.
     *
     * @param \Servelat\Components\ProcessManager\ProcessInterface $process
     * @return $this
     */
    public function addProcess(ProcessInterface $process)
    {
        $processKey = $this->processQueue->count();
        $this->processQueue->add($processKey, $process);
        $this->streams[$processKey] = $process->getStreams();

        return $this;
    }

    /**
     * Count processes in a queue.
     *
     * @return int
     */
    public function countProcesses()
    {
        return $this->processQueue->count();
    }

    public function streamSelect()
    {

    }

    /**
     * Check if stream is a real stream resource.
     *
     * @param resource $stream
     * @return bool
     */
    protected function checkIsStream($stream)
    {
        return is_resource($stream) && get_resource_type($stream) === 'stream';
    }
}