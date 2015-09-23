<?php


namespace Servelat\Components\TaskManager;

/**
 * Interface TaskInterface.
 * Base task interface.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface TaskInterface
{
    /**
     * Get task identifier.
     *
     * @return integer
     */
    public function getId();

    /**
     * Get pid of task owner.
     *
     * @return integer
     */
    public function getOwnerPid();

    /**
     * The time to wait until the task is processed.
     *
     * @return integer
     */
    public function getTimeout();

    /**
     * Number of task instances that should be run simultaneously.
     *
     * @return integer
     */
    public function getNumberOfInstances();

    /**
     * Get an abstract value called payload.
     * It can be anything up to the handler.
     *
     * @return mixed
     */
    public function getPayload();
}