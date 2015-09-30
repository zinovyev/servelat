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
     * @var string
     */
    protected $serializedMessage;

    /**
     * @param string $serializedMessage
     */
    public function __construct($serializedMessage)
    {
        $this->serializedMessage = $serializedMessage;
    }

    /**
     * @return MessageInterface
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param MessageInterface $message
     * @return UnserializeMessageEvent
     */
    public function setMessage(MessageInterface $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getSerializedMessage()
    {
        return $this->serializedMessage;
    }
}