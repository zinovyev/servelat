<?php

namespace servelat\supervisor;

/**
 * Interface TaskInterface.
 * Base task interface.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface TaskInterface
{
    /**
     * @const Execute plain PHP code
     */
    const TYPE_RAW = 'type_raw';

    /**
     * @const Execute standalone PHP file
     */
    const TYPE_STANDALONE = 'type_standalone';

    /**
     * Get task type.
     * The type is used to select the proper task runner.
     * Allowed values are: TaskInterface::TYPE_RAW and TaskInterface::TYPE_STANDALONE.
     *
     * @return string
     */
    public function getType();

    /**
     * Keep task running.
     * Reload task when the process was stopped.
     *
     * @return boolean
     */
    public function keepAlive();

    /**
     * Count instances.
     * Exact count of instances running at the same time.
     *
     * @return integer
     */
    public function getInstancesCount();

    /**
     * Get payload.
     * Plain PHP for type TaskInterface::TYPE_RAW. Filename for type TaskInterface::TYPE_STANDALONE.
     *
     * @return string
     */
    public function getPayload();

    /**
     * Get timestamp.
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Needs response.
     * Client is waiting for the response or not.
     *
     * @return boolean
     */
    public function needsResponse();

}