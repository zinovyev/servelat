<?php


namespace Servelat\Components\ProcessManager;

/**
 * Interface ProcessInterface.
 * Base process interface.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface ProcessInterface
{
    /**
     * Get list of streams (stdin, stdout, stderr).
     * Every stream must be a resource of type "stream".
     *
     * @return array
     */
    public function getStreams();

    /**
     * Add new line from stdout/stderr.
     *
     * @param $line
     * @return $this
     */
    public function addOutputLine($line);

    /**
     * Get array of process output.
     *
     * @return array
     */
    public function getOutputLines();
}