<?php

namespace Servelat\Components\TaskManager;

use Servelat\Components\ProcessManager\Exceptions\RuntimeException;
use Servelat\Components\ProcessManager\ProcessInterface;

/**
 * Class TaskResult.
 * Task result wrapper.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class TaskResult
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $ownerPid;

    /**
     * @var array
     */
    protected $outputLines;

    /**
     * @var int
     */
    protected $exitCode;

    /**
     * @param string $id
     * @param integer $ownerPid
     * @param array $outputLines
     * @param integer $exitCode
     */
    public function __construct($id, $ownerPid, $exitCode, array $outputLines = [])
    {
        $this->id = $id;
        $this->ownerPid = $ownerPid;
        $this->outputLines = $outputLines;
        $this->exitCode = $exitCode;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getOwnerPid()
    {
        return $this->ownerPid;
    }

    /**
     * @return array
     */
    public function getOutputLines()
    {
        return $this->outputLines;
    }

    /**
     * @return int
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }
}