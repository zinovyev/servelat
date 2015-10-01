<?php


namespace Servelat\Components\MessageBroker\Serializers;


use Servelat\Components\MessageBroker\MessageInterface;
use Servelat\Components\MessageBroker\Messages\JsonMessage;
use Servelat\Components\MessageBroker\SerializerInterface;
use Servelat\Components\ProcessManager\Exceptions\RuntimeException;
use Servelat\Components\MessageBroker\Events\UnserializeMessageEvent;

/**
 * Class JsonMessageSerializer.
 * Serializer for the json message.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class JsonMessageSerializer implements SerializerInterface
{
    /**
     * @const string
     */
    const JFON_FORMAT_PREFIX = '#json#';

    /**
     * Possible attribute name for the payload field.
     *
     * @const string
     */
    const ATTR_PAYLOAD = 'payload';

    /**
     * Possible attribute name for the routing key field.
     *
     * @const string
     */
    const ATTR_ROUTING_KEY = 'rkey';

    /**
     * Check if parser is suitable for the type of messages.
     *
     * @param MessageInterface $message
     * @return boolean
     */
    public function canSerialize(MessageInterface $message)
    {
        return $message instanceof JsonMessage;
    }

    /**
     * Check if parser is suitable for the type of serialized messages.
     *
     * @param string $serializedMessage
     * @return boolean
     */
    public function canUnserialize($serializedMessage)
    {
        return self::JFON_FORMAT_PREFIX === mb_strcut($serializedMessage, 0, strlen(self::JFON_FORMAT_PREFIX));
    }

    /**
     * Serialize a message.
     *
     * @param MessageInterface $message
     * @return string
     */
    public function seriazlie(MessageInterface $message)
    {
        $representation = [
            self::ATTR_ROUTING_KEY  => $message->getRoutingKey(),
            self::ATTR_PAYLOAD      => $message->getPayload(),
        ];
        $serialized = sprintf(
            '%s%s',
            self::JFON_FORMAT_PREFIX,
            json_encode($representation)
        );

        return $serialized;
    }

    /**
     * Unserialize a message.
     *
     * @param string $serializedMessage
     * @return MessageInterface
     * @throws RuntimeException
     */
    public function unserialize($serializedMessage)
    {
        $weight = mb_strcut($serializedMessage, strlen(self::JFON_FORMAT_PREFIX));

        // Unserialize
        $messageData = json_decode($weight, true);
        if (!$messageData || !isset($messageData[self::ATTR_ROUTING_KEY], $messageData[self::ATTR_PAYLOAD])) {
            throw new RuntimeException('Failed to unserialize the message');
        }
        $jsonMessage = new JsonMessage(
            $messageData[self::ATTR_ROUTING_KEY],
            $messageData[self::ATTR_PAYLOAD]
        );

        return $jsonMessage;
    }

    /**
     * Handle unserialize message event.
     *
     * @param \Servelat\Components\MessageBroker\Events\UnserializeMessageEvent $event
     * @throws \Servelat\Components\ProcessManager\Exceptions\RuntimeException
     */
    public function onMessageUnserializeEvent(UnserializeMessageEvent $event)
    {
        $serializedMessage = $event->getSerializedMessage();
        if ($this->canUnserialize($serializedMessage)) {
            $message = $this->unserialize($serializedMessage);
            $event
                ->setMessage($message)
                ->stopPropagation()
            ;
        }
    }
}