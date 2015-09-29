<?php


namespace Servelat\Components\MessageBroker;

/**
 * Interface MessageInterface.
 * Message representation.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface MessageInterface
{
    /**
     * @param mixed $payload Any abstract value
     */
    public function __construct($routingKey, $payload = null);

    /**
     * Get the payload of the message.
     *
     * @return mixed
     */
    public function getPayload();

    /**
     * Get the routing key for the message.
     *
     * @return string
     */
    public function getRoutingKey();

}