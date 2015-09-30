<?php


namespace Servelat\Components\MessageBroker;

use Servelat\Components\MessageBroker\Events\AfterUnserializeMessageEvent;
use Servelat\Components\MessageBroker\Events\UnserializeMessageEvent;
use Servelat\Components\TaskManager\Events\AfterProcessTaskEvent;
use Servelat\ServelatEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * Class MessageBroker.
 * The message broker is used to handle in/out messages.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class MessageBroker
{
    /**
     * Routing key for the task manager component.
     *
     * @const string
     */
    const ROUTING_KEY_TASK_MANAGER = 'task_manager';

    /**
     * @var EventDispatcher
     */
    protected $dispatcher;

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
     */
    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Process new request message.
     *
     * @param string $serializedMessage
     * @return $this
     */
    public function routeRequestMessage($serializedMessage)
    {
        // Try to unserialize message
        $unserializeMessageEvent = new UnserializeMessageEvent($serializedMessage);
        $this->dispatcher->dispatch(
            ServelatEvents::MESSAGE_BROKER_UNSERIALIZE_MESSAGE,
            $unserializeMessageEvent
        );

        // Route data if any routing key was found
        $message = $unserializeMessageEvent->getMessage();
        if (
            $message instanceof MessageInterface
            && null !== $routingKey = $message->getRoutingKey()
        ) {
            $afterUnserializeMessageEvent = new AfterUnserializeMessageEvent(
                $routingKey,
                $message->getPayload()
            );
            $this->dispatcher->dispatch(
                ServelatEvents::MESSAGE_BROKER_AFTER_UNSERIALIZE_MESSAGE,
                $afterUnserializeMessageEvent
            );
        }

        return $this;
    }
}