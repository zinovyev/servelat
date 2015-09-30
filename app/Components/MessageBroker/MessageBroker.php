<?php


namespace Servelat\Components\MessageBroker;

use Servelat\Components\MessageBroker\Events\AfterUnserializeMessageEvent;
use Servelat\Components\MessageBroker\Events\UnserializeMessageEvent;
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
     * @param \Servelat\Components\MessageBroker\MessageInterface $message
     * @return $this
     */
    public function routeRequestMessage(MessageInterface $message)
    {
        // Try to unserialize message
        $unserializeMessageEvent = new UnserializeMessageEvent($message);
        $this->dispatcher->dispatch(
            ServelatEvents::MESSAGE_BROKER_UNSERIALIZE_MESSAGE,
            $unserializeMessageEvent
        );

        // Route data if any routing key was found
        if (null !== $routingKey = $message->getRoutingKey()) {
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