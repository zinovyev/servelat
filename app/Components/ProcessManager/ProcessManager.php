<?php


namespace Servelat\Components\ProcessManager;


use Servelat\Base\Exceptions\InvalidArgumentException;
use Servelat\Components\ProcessManager\Events\ProcessClosedEvent;
use Servelat\Components\ProcessManager\Events\ProcessFailedEvent;
use Servelat\Components\ProcessManager\Events\ProcessOutputEvent;
use Servelat\Components\ProcessManager\Exceptions\RuntimeException;
use Servelat\Components\TaskManager\Events\AfterProcessTaskEvent;
use Servelat\ServelatEvents;
use Servelat\Tests\Bootstrap\ServerApplicationAwareTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class ProcessManager.
 * Process manager which can call select() function to detect activity of the process.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class ProcessManager
{
    /**
     * Stream resource for stdin.
     *
     * @const string
     */
    const STREAM_IN = 'stream_in';

    /**
     * Stream resource for stdout.
     *
     * @const string
     */
    const STREAM_OUT = 'stream_out';

    /**
     * Stream resource for stderr.
     *
     * @const string
     */
    const STREAM_ERR = 'stream_err';

    /**
     * Time in microseconds to wait
     * for any process activity.
     *
     * @const int
     */
    const WAIT_TIME = 500;

    /**
     * @var \SplQueue
     */
    protected $processQueue;

    /**
     * @var \ArrayObject
     */
    protected $streamSuites;

    /**
     * @var array
     */
    protected $streamToKeyMap = [];

    /**
     * @var array
     */
    protected $stdInStreams = [];

    /**
     * @var array
     */
    protected $stdOutStreams = [];

    /**
     * @var array
     */
    protected $stdErrStreams = [];

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatcher;

    /**
     * @param \SplQueue $processQueue
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     */
    public function __construct(\SplQueue $processQueue, EventDispatcher $dispatcher)
    {
        $this->processQueue = new \ArrayObject();
        $this->streamSuites = new \ArrayObject();
        $this->dispatcher = $dispatcher;
    }

    /**
     * Handle after process task event.
     *
     * @param \Servelat\Components\TaskManager\Events\AfterProcessTaskEvent $event
     * @return $this
     */
    public function onAfterProcessTask(AfterProcessTaskEvent $event)
    {
        $process = $event->getProcess();
        $this->addProcess($process);

        return $this;
    }

    /**
     * Add new process to the queue.
     *
     * @param \Servelat\Components\ProcessManager\ProcessInterface $process
     * @return $this
     * @throws \Servelat\Base\Exceptions\InvalidArgumentException
     */
    public function addProcess(ProcessInterface $process)
    {
        $processKey = $this->processQueue->count();
        $streamSuite = $process->getStreams();
        $this->processQueue[$processKey] = $process;
        $this->streamSuites[$processKey] = $streamSuite;

        // Stdin stream
        if (!isset($streamSuite[0]) || !$this->checkIsStream($streamSuite[0])) {
            throw new InvalidArgumentException('Every process should have an stdin stream #0 of type resource!');
        }
        $stdInStreamHash = $this->buildStreamHash($streamSuite[0]);
        $this->streamToKeyMap[$stdInStreamHash] = $processKey;
        $this->stdInStreams[$stdInStreamHash] = $streamSuite[0];

        // Stdout stream
        if (!isset($streamSuite[1]) || !$this->checkIsStream($streamSuite[1])) {
            throw new InvalidArgumentException('Every process should have an stdout stream #1 of type resource!');
        }
        $stdOutStreamHash = $this->buildStreamHash($streamSuite[1]);
        $this->streamToKeyMap[$stdOutStreamHash] = $processKey;
        $this->stdOutStreams[$stdOutStreamHash] = $streamSuite[1];

        // Stderr stream
        if (!isset($streamSuite[2]) || !$this->checkIsStream($streamSuite[2])) {
            throw new InvalidArgumentException('Every process should have an stderr stream #2 of type resource!');
        }
        $stdErrStreamHash = $this->buildStreamHash($streamSuite[2]);
        $this->streamToKeyMap[$stdErrStreamHash] = $processKey;
        $this->stdErrStreams[$stdErrStreamHash] = $streamSuite[2];

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

    /**
     * Count stream suites.
     *
     * @return int
     */
    public function countStreamSuites()
    {
        return $this->streamSuites->count();
    }

    /**
     * Runs the equivalent of the select() system call on the processQueue queue.
     * Returns the number of closed processes.
     *
     * @return integer
     * @throws \Servelat\Components\ProcessManager\Exceptions\RuntimeException
     */
    public function streamSelect()
    {
        if ($this->countProcesses() === 0) {
            return 0;
        }

        // #1. Build read streams
        //
        $readStreams = $closedProcesses = $failedProcesses = [];
        $writeStreams = $errStreams = null;
        foreach ($this->streamSuites as $streamSuiteKey => $streamSuite) {
            $stdInStream = isset($streamSuite[0]) ? $streamSuite[0] : null;
            $stdOutStream = isset($streamSuite[1]) ? $streamSuite[1] : null;
            $stdErrStream = isset($streamSuite[2]) ? $streamSuite[2] : null;
            $readStreams[] = $stdOutStream;
            $readStreams[] = $stdErrStream;
        }
        array_filter($readStreams);

        // #2. Run system select()
        //
        $closedProcessKeys = $failedProcessKeys = [];
        if (isset($readStreams[0])) {
            $affectedStreamsCount = stream_select($readStreams, $writeStreams, $errStreams, 0, self::WAIT_TIME);
            if ($affectedStreamsCount !== false && $affectedStreamsCount > 0) {
                foreach ($readStreams as $readStream) {
                    $line = fgets($readStream);
                    if (false !== $line) {
                        $processKey = $this->getProcessKeyByStream($readStream);
                        /** @var ProcessInterface $process */
                        $process = $this->processQueue[$processKey];
                        $line = trim ($line);
                        $process->addOutputLine($line);

                        // Send output to the dispatcher
                        $processOutputEvent = new ProcessOutputEvent($process, $line);
                        $this->dispatcher->dispatch(
                            ServelatEvents::PROCESS_MANAGER_PROCESS_OUTPUT,
                            $processOutputEvent
                        );

                        if (self::STREAM_ERR === $this->getStreamType($readStream)) {
                            $failedProcessKeys[$processKey] = $processKey;
                            $closedProcessKeys[] = $processKey;

                            // Send failed process to the dispatcher
                            $processFailedEvent = new ProcessFailedEvent($process);
                            $this->dispatcher->dispatch(
                                ServelatEvents::PROCESS_MANAGER_PROCESS_FAILED,
                                $processFailedEvent
                            );
                        }
                    } else {
                        $processKey = $this->getProcessKeyByStream($readStream);
                        $closedProcessKeys[] = $processKey;
                    }

                }

            } else {
                // No stream output cached
                usleep(self::WAIT_TIME);
            }
        }

        $closedProcessKeys = array_unique($closedProcessKeys);

        // #3. Close processes and process streams
        //
        $closedProcessCount = 0;
        foreach ($closedProcessKeys as $closedProcessKey) {
            // Close all streams
            if (isset($this->streamSuites[$closedProcessKey])) {
                foreach ($this->streamSuites[$closedProcessKey] as $stream) {
                    if ($this->checkIsStream($stream)) {
                        unset($this->streamToKeyMap[$this->buildStreamHash($stream)]);
                        fclose($stream);
                    }
                }
                unset($this->streamSuites[$closedProcessKey]);
                unset($this->stdInStreams[$closedProcessKey]);
                unset($this->stdOutStreams[$closedProcessKey]);
                unset($this->stdErrStreams[$closedProcessKey]);
            }

            // Close process
            if (isset($this->processQueue[$closedProcessKey])) {
                /** @var ProcessInterface $process */
                $process = $this->processQueue[$closedProcessKey];
                if (isset($failedProcessKeys[$closedProcessKey])) {
                    $process->setExitCode(1);
                } else {
                    $process->setExitCode(0);
                }
                $process->close();
                unset($this->processQueue[$closedProcessKey]);

                // Dispatch event
                $processClosedEvent = new ProcessClosedEvent($process);
                $this->dispatcher->dispatch(
                    ServelatEvents::PROCESS_MANAGER_PROCESS_CLOSED,
                    $processClosedEvent
                );

                ++$closedProcessCount;
            }
        }

        return $closedProcessCount;
    }

    /**
     * Build hash of stream
     *
     * @param resource$stream
     * @return string
     */
    protected function buildStreamHash($stream)
    {
        return (string) $stream;
    }

    /**
     * Get process key by stream.
     *
     * @param resource $stream
     * @return string|null
     */
    protected function getProcessKeyByStream($stream)
    {
        $streamHash = $this->buildStreamHash($stream);
        return isset($this->streamToKeyMap[$streamHash])
            ? $this->streamToKeyMap[$streamHash] : null ;
    }

    /**
     * Get type of stream.
     *
     * @param $stream
     * @return string
     * @throws \Servelat\Components\ProcessManager\Exceptions\RuntimeException
     */
    protected function getStreamType($stream)
    {
        $streamHash = $this->buildStreamHash($stream);

        if (isset($this->stdOutStreams[$streamHash])) {
            return self::STREAM_OUT;
        } elseif (isset($this->stdErrStreams[$streamHash])) {
            return self::STREAM_ERR;
        } elseif (isset($this->stdInStreams[$streamHash])) {
            return self::STREAM_IN;
        } else {
            throw new RuntimeException('Stream of unknown type detected!');
        }
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