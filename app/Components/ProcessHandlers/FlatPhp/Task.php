<?php


namespace Servelat\Components\ProcessHandlers\FlatPhp;


use Servelat\Components\TaskManager\TaskInterface;

/**
 * Class Task.
 * This class describes a flat-php task. The code (payload parameter)
 * will be executed via the `php -r <your code>` command.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class Task implements TaskInterface
{
    /**
     * Task identifier.
     * Can be whatever you like.
     *
     * @var string
     */
    protected $id;

    /**
     * Process owner (task creator) PID.
     *
     * @var integer
     */
    protected $ownerPid = 0;

    /**
     * Seconds to live.
     *
     * @var integer
     */
    protected $timeout = 0;

    /**
     * Number of task instances that should be run simultaneously.
     *
     * @var integer
     */
    protected $numberOfInstances;

    /**
     * The PHP code.
     *
     * @var string
     */
    protected $payload;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return Task
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getOwnerPid()
    {
        return $this->ownerPid;
    }

    /**
     * @param int $ownerPid
     * @return Task
     */
    public function setOwnerPid($ownerPid)
    {
        $this->ownerPid = (int) $ownerPid;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     * @return Task
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @return int
     */
    public function getNumberOfInstances()
    {
        return $this->numberOfInstances;
    }

    /**
     * @param int $numberOfInstances
     * @return Task
     */
    public function setNumberOfInstances($numberOfInstances)
    {
        $this->numberOfInstances = (int) $numberOfInstances;

        return $this;
    }

    /**
     * @return string
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param string $payload
     * @return Task
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;

        return $this;
    }
}