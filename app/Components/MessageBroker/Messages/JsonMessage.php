<?php


namespace Servelat\Components\MessageBroker\Messages;

use Servelat\Components\ProcessManager\Exceptions\RuntimeException;
use Servelat\Components\MessageBroker\MessageInterface;

/**
 * Class JsonMessage.
 * Json message representation.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class JsonMessage implements MessageInterface
{
    /**
     * @var string
     */
    protected $routingKey;

    /**
     * @var string|null
     */
    protected $payload;

    /**
     * @param mixed $payload Any value
     */
    public function __construct($routingKey, $payload = null)
    {
        $this->routingKey = $routingKey;
        $this->payload = $payload;
    }

    /**
     * Get the payload of the message.
     *
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Get the routing key for the message.
     *
     * @return string
     */
    public function getRoutingKey()
    {
        return $this->routingKey;
    }
}