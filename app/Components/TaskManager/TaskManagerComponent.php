<?php

namespace Servelat\Components\TaskManager;

use Servelat\Base\AbstractApplication;
use Servelat\Base\ComponentInterface;
use Servelat\Components\TaskManager\Handlers\Idle\EmptyTask;
use Servelat\Components\TaskManager\Handlers\Idle\IdleHandler;
use Servelat\ServelatEvents;

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
        $dispatcher = $application->getDispatcher();
        $container = $application->getContainer();

        // Register task manager as a service
        $container['task_manager.task_manager'] = function ($c) use ($dispatcher) {
            return new TaskManager($c['queues.default_queue'], $dispatcher);
        };

        // Register empty task factory
        $container['task_manager.empty_task'] = $container->factory(function ($c) {
            $emptyTask = new EmptyTask();
            $emptyTask->setId(time());

            return $emptyTask;
        });

        // Add Idle handler as event listener
        $dispatcher->addListener(
            ServelatEvents::TASK_MANAGER_PROCESS_TASK,
            [new IdleHandler(), 'onProcessTask'],
            10
        );
        $dispatcher->addListener(
            ServelatEvents::MESSAGE_BROKER_AFTER_UNSERIALIZE_MESSAGE,
            [$container['task_manager.task_manager'], 'onAfterMessageUnserializeEvent'],
            10
        );
        $dispatcher->addListener(
            ServelatEvents::PROCESS_MANAGER_PROCESS_CLOSED,
            [$container['task_manager.task_manager'], 'onAfterProcessClosed'],
            10
        );
        $dispatcher->addListener(
            ServelatEvents::PROCESS_MANAGER_PROCESS_FAILED,
            [$container['task_manager.task_manager'], 'onAfterProcessFailed'],
            10
        );
        $dispatcher->addListener(
            ServelatEvents::PROCESS_MANAGER_PROCESS_OUTPUT,
            [$container['task_manager.task_manager'], 'onAfterProcessOutput'],
            10
        );

        return $this;
    }
}