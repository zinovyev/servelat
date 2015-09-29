<?php


namespace Servelat\Components\ProcessHandlers;


use Servelat\Base\AbstractApplication;
use Servelat\Base\ComponentInterface;
use Servelat\Components\ProcessHandlers\FlatPhp\Task;
use Servelat\Components\ProcessHandlers\FlatPhp\TaskHandler;
use Servelat\ServelatEvents;

/**
 * Class ProcessHandlersComponent.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class ProcessHandlersComponent implements ComponentInterface
{

    /**
     * Get the name of the component.
     * Used to register unique component.
     *
     * @return string
     */
    public function getName()
    {
        return 'process_handlers';
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

        // Register empty task factory
        $container['process_handlers.flat_php_task'] = $container->factory(function ($c) {
            $flatTask = new Task();
            $flatTask->setId(time());

            return $flatTask;
        });

        $dispatcher->addListener(
            ServelatEvents::TASK_MANAGER_PROCESS_TASK,
            [new TaskHandler(), 'onProcessTask'],
            100
        );
    }
}