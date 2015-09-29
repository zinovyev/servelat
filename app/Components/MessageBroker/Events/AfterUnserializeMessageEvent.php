<?php


namespace Servelat\Components\MessageBroker\Events;


use Symfony\Component\EventDispatcher\Event;

/**
 * Class AfterUnserializeMessageEvent.
 * The event AfterUnserializeMessageEvent is throu.
 *
 * @author Ivan Zinovyev <i.zinovyev@rambler-co.ru>
 */
class AfterUnserializeMessageEvent extends Event
{
    /**
     * Can be whatever you want.
     *
     * @var mixed
     */
    protected $payload;

    /**
     * A routing key specifies the end point of the message.
     *
     * @var string
     */
    protected $routingKey;

    /**
     * @param string $routingKey A routing key specifies the end point of the message.
     * @param mixed $payload A payload can be whatever you want.
     */
    public function __construct($routingKey, $payload = null)
    {
        $this->payload = $payload;
        $this->routingKey = $routingKey;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @return string
     */
    public function getRoutingKey()
    {
        return $this->routingKey;
    }
}