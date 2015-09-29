<?php


namespace Servelat\Components\MessageBroker\Events;


use Symfony\Component\EventDispatcher\Event;
use Servelat\Components\MessageBroker\MessageInterface;

/**
 * Class UnserializeMessageEvent.
 * The UnserializeMessageEvent is thrown when the message is parsed.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class UnserializeMessageEvent extends Event
{
    /**
     * @var \Servelat\Components\MessageBroker\MessageInterface
     */
    protected $message;

    /**
     * @param MessageInterface $message
     */
    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
    }

    /**
     * @return MessageInterface
     */
    public function getMessage()
    {
        return $this->message;
    }
}