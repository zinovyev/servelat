<?php

namespace Servelat\Components\TaskManager;

use Servelat\Base\AbstractApplication;
use Servelat\Base\ComponentInterface;

/**
 * Class TaskManagerComponent.
 * Class TaskManagerComponent description.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class TaskManagerComponent implements  ComponentInterface
{

    /**
     * Get the name of the component.
     * Used to register unique component.
     *
     * @return string
     */
    public function getName()
    {
        return 'task_manager';
    }

    /**
     * Register component.
     * Component can access application configuration, dispatcher and
     *
     * @param AbstractApplication $application
     * @return mixed
     */
    public function register(AbstractApplication $application)
    {
    }
}