<?php


namespace servelat\Components\ProcessManager;

/**
 * Interface ProcessInterface.
 * The base interface of a process.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface ProcessInterface
{
    /**
     * Execute process.
     * And get array of streams.
     *
     * @return array
     */
    public function exec();

    /**
     * Get resource of type "process".
     *
     * @return resource
     */
    public function getResource();

    /**
     * Get process status.
     * This information is not so really reliable.
     *
     * @return array
     */
    public function getStatus();

    /**
     * Get PID of the process.
     *
     * @return int
     */
    public function getPid();

    /**
     * Check if process is running.
     *
     * @return bool
     */
    public function isRunning();

    /**
     * Set running status.
     *
     * @param bool $running
     * @return $this
     */
    public function setRunning($running);

    /**
     * Get exit code of the process.
     *
     * @return int
     */
    public function getExitCode();

    /**
     * Set exit code of the process
     *
     * @param int $exitCode
     * @return $this
     */
    public function setExitCode($exitCode);

    /**
     * Get all lines returned by the process.
     *
     * @return array
     */
    public function getReturnedLines();

    /**
     * Add another line returned by the process.
     *
     * @param string $line
     * @return $this
     */
    public function addReturnedLine($line);
}