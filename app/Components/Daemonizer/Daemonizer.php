<?php


namespace Servelat\Components\Daemonizer;

use Servelat\Base\Exceptions\ServelatException;

// Needed to get the proper argv list
ini_set('register_argc_argv', 'true');

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
     * @var bool
     */
    protected $testMode = false;

    /**
     * @var bool
     */
    protected $daemonized = false;

    /**
     * @param string $pidFile The filename of the PID file.
     * @param bool $testMode Set if app is currently running in the test mode or not (won't start child process in the test mode).
     */
    public function __construct($pidFile, $testMode = false)
    {
        $this->pidFile = $pidFile;
        $this->pid = getmypid();
        $this->testMode = !!$testMode;

        if (!is_file($this->pidFile)) {
            touch($pidFile);
        }
    }

    /**
     * Daemonize current process.
     *
     * @throws \Servelat\Base\Exceptions\ServelatException
     */
    public function daemonize()
    {
        global $argc, $argv;

        if (true === $this->daemonized) {
            throw new ServelatException('Already daemonized!');
        }

        // In parent process - return child PID
        if (0 === $this->getActivePid()) {

            // If NOT run The filename of the PID file.ing in the test mode
            if (!$this->testMode) {
                $cmd = sprintf(
                    'exec %s %s %s &>/dev/null & echo $!',
                    PHP_BINARY,
                    $this->getRunningFile(),
                    implode(' ', array_slice($argv, 1)) // Input arguments
                );
                $pid = exec($cmd);
            // Test mode
            } else {
                $pid = $this->pid;
            }

            if (!$this->setActivePid($pid)) {
                throw new \RuntimeException('Failed to save child PID.');
            }

            // Set 'daemonized' mark on the process
            $this->daemonized = true;

            return $this->getActivePid();

        // In child process - return 0
        } elseif ($this->pid === $this->getActivePid()) {

            // Set 'daemonized' mark on the process
            $this->daemonized = true;

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
    public function getRunningFile()
    {
        $backtrace = debug_backtrace();
        return end($backtrace)['file'];
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

        return (int) $pid;
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