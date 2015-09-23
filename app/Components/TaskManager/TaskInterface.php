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
     * @return \Servelat\Components\ProcessManager\ProcessInterface
     */
    public function execute();
}