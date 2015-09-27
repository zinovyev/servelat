<?php


namespace Servelat\Components\ProcessManager\Processes;

use Servelat\Components\ProcessManager\ProcessInterface;

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
     * Get list of streams (stdin, stdout, stderr).
     * Every stream must be a resource of type "stream".
     *
     * @return array
     */
    public function getStreams()
    {
        if (null === $this->streams) {
            $this->streams = [
                fopen('/dev/null', 'w'),    // Process stdin stream
                fopen('/dev/null', 'r'),    // Process stdout stream
                fopen('/dev/null', 'r'),    // Process stderr stream
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
}