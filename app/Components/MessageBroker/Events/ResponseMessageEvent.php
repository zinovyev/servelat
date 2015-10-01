<?php


namespace Servelat\Components\MessageBroker\Events;


use Servelat\Components\MessageBroker\MessageInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ResponseMessageEvent.
 * Create a response event.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class ResponseMessageEvent extends Event
{
    /**
     * @var \Servelat\Components\MessageBroker\MessageInterface
     */
    protected $message;

    /**
     * @param \Servelat\Components\MessageBroker\MessageInterface $message
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