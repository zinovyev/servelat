<?php


namespace Servelat\Components\MessageBroker;


use Servelat\Base\AbstractApplication;
use Servelat\Base\ComponentInterface;

class MessageBrokerComponent implements ComponentInterface
{

    /**
     * Get the name of the component.
     * Used to register unique component.
     *
     * @return string
     */
    public function getName()
    {
        // TODO: Implement getName() method.
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
        // TODO: Implement register() method.
    }
}