<?php


namespace Servelat\Components\MessageBroker;

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

    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function addRequestMessage(MessageInterface $message)
    {
        $unserializeMessageEvent = new UnserializeMessageEvent($message);
        $this->dispatcher->dispatch(
            ServelatEvents::MESSAGE_BROKER_UNSERIALIZE_MESSAGE,
            $unserializeMessageEvent
        );
    }
}