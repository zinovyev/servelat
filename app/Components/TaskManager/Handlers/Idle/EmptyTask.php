<?php


namespace Servelat\Components\TaskManager\Handlers\Idle;


use Servelat\Components\TaskManager\TaskInterface;

class EmptyTask implements TaskInterface
{
    /**
     * Task identifier.
     *
     * @var integer
     */
    protected $id;

    /**
     * Get task identifier.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return EmptyTask
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get pid of task owner.
     *
     * @return integer|null
     */
    public function getOwnerPid()
    {
        return null;
    }

    /**
     * The time to wait until the task is processed.
     *
     * @return integer|null
     */
    public function getTimeout()
    {
        return null;
    }

    /**
     * Number of task instances that should be run simultaneously.
     *
     * @return integer
     */
    public function getNumberOfInstances()
    {
        return 1;
    }

    /**
     * Get an value called payload.
     * It can be anything up to the handler.
     *
     * @return mixed
     */
    public function getPayload()
    {
        return null;
    }
}