<?php


namespace Servelat\Components\Queues;


use Servelat\Base\AbstractApplication;
use Servelat\Base\ComponentInterface;

/**
 * Class QueuesComponent.
 * Queues collection.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class QueuesComponent implements ComponentInterface
{
    /**
     * Get the name of the component.
     * Used to register unique component.
     *
     * @return string
     */
    public function getName()
    {
        return 'queues';
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
        $container = $application->getContainer();

        // Set default queue factory
        $container['queues.default_queue'] = $container->factory(function ($c) {
            return new DefaultQueue();
        });
    }
}