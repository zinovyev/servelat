<?php


namespace Servelat\Components\ProcessHandlers\FlatPhp;


use Servelat\Components\TaskManager\TaskInterface;

class Task implements TaskInterface
{

    /**
     * Get task identifier.
     *
     * @return integer
     */
    public function getId()
    {
        // TODO: Implement getId() method.
    }

    /**
     * Get pid of task owner.
     *
     * @return integer
     */
    public function getOwnerPid()
    {
        // TODO: Implement getOwnerPid() method.
    }

    /**
     * The time to wait until the task is processed.
     *
     * @return integer
     */
    public function getTimeout()
    {
        // TODO: Implement getTimeout() method.
    }

    /**
     * Number of task instances that should be run simultaneously.
     *
     * @return integer
     */
    public function getNumberOfInstances()
    {
        // TODO: Implement getNumberOfInstances() method.
    }

    /**
     * Get an value called payload.
     * It can be anything up to the handler.
     *
     * @return mixed
     */
    public function getPayload()
    {
        // TODO: Implement getPayload() method.
    }
}