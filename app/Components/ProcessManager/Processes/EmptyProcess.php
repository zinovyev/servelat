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
}