<?php


namespace Servelat\Components\MessageBroker;


use Servelat\Base\AbstractApplication;
use Servelat\Base\ComponentInterface;
use Servelat\Components\MessageBroker\Serializers\JsonMessageSerializer;
use Servelat\ServelatEvents;

/**
 * Class MessageBrokerComponent.
 * The message broker component. Used to route and serialize/unserialize messages.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
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
        return 'message_broker';
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

        $container['message_broker.message_broker'] = function ($c) use ($dispatcher) {
            return new MessageBroker($dispatcher);
        };
        $container['message_broker.json_message_serializer'] = function ($c) {
            return new JsonMessageSerializer();
        };

        $dispatcher->addListener(
            ServelatEvents::MESSAGE_BROKER_UNSERIALIZE_MESSAGE,
            [$container['message_broker.json_message_serializer'], 'onMessageUnserializeEvent']
        );
        $dispatcher->addListener(
            ServelatEvents::MESSAGE_BROKER_RESPONSE_MESSAGE,
            [$container['message_broker.message_broker'], 'onResponseMessage']
        );
    }
}