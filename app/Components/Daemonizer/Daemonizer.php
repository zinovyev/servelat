<?php


namespace Servelat\Components\Daemonizer;

use Guzzle\Common\Exception\RuntimeException;
use Servelat\Base\Exceptions\ServelatException;

/**
 * Class Daemonizer.
 * A helper class used to daemonize
 * currently running process as a singleton process.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class Daemonizer
{
    /**
     * @var int
     */
    protected $pid;

    /**
     * @var string
     */
    protected $pidFile;

    /**
     * @param string $pidFile
     */
    public function __construct($pidFile)
    {
        $this->pidFile = $pidFile;
    }

    /**
     * Daemonize current process.
     *
     * @throws \Servelat\Base\Exceptions\ServelatException
     */
    public function daemonize()
    {
        // In parent process - return child PID
        if ($this->getActivePid() === 0) {

            $cmd = sprintf(
                '%s %s &>/dev/null & echo $?',
                PHP_BINARY,
                $this->getCurrentlyRunningFile()
            );
            $pid = exec($cmd);

            if (!$this->setActivePid($pid)) {
                throw new \RuntimeException('Failed to save child PID.');
            }

            return $this->getActivePid();

        // In child process - return 0
        } elseif ($this->getActivePid() === $this->getMyPid()) {

            // Become the session leader
            posix_setsid();

            return 0;
        // Duplicate run
        } else {
            throw new \LogicException(sprintf(
                'Another process with PID %d is already running',
                $this->getActivePid()
            ));
        }
    }

    /**
     * Get the name of currently running file.
     *
     * @return string
     */
    public function getCurrentlyRunningFile()
    {
        $backtrace = debug_backtrace();
        return end($backtrace)['file'];
    }

    /**
     * @return int
     */
    public function getMyPid()
    {
        if (null === $this->pid) {
            $this->pid = getmypid();
        }

        return $this->pid;
    }

    /**
     * Get active PID value.
     *
     * @return int
     * @throws \Servelat\Base\Exceptions\ServelatException
     */
    public function getActivePid()
    {
        if (!isset($this->pidFile)) {
            throw new ServelatException('PID file is not set.');
        }
        if (!is_file($this->pidFile) || !is_readable($this->pidFile)) {
            throw new ServelatException('Can not read from the PID file. PID file does not exists or is not readable.');
        }

        $pid = file_get_contents($this->pidFile) ?: 0;  // Get stored PID value
        if ($pid && !posix_kill($pid, 0)) {             // Check if PID is active
            $pid = 0;
        }

        return $pid;
    }

    /**
     * Save active pid value.
     *
     * @param int $pid The PID of the process.
     * @return bool
     * @throws \Servelat\Base\Exceptions\ServelatException
     */
    public function setActivePid($pid)
    {
        if (!isset($this->pidFile)) {
            throw new ServelatException('PID file is not set.');
        }
        if (!is_file($this->pidFile) || !is_writable($this->pidFile)) {
            throw new ServelatException('Can write to the PID file. PID file does not exists or is not readable.');
        }

        return file_put_contents($this->pidFile, $pid) !== false;
    }
}