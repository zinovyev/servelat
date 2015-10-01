<?php


namespace Servelat\Components\MessageBroker;

/**
 * Interface ParserInterface.
 * Used to parse serialized broker messages.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface SerializerInterface
{
    /**
     * Check if parser is suitable for the type of messages.
     *
     * @param MessageInterface $message
     * @return boolean
     */
    public function canSerialize(MessageInterface $message);

    /**
     * Check if parser is suitable for the type of serialized messages.
     *
     * @param string $serializedMessage
     * @return boolean
     */
    public function canUnserialize($serializedMessage);

    /**
     * Serialize a message.
     *
     * @param MessageInterface $message
     * @return string
     */
    public function seriazlie(MessageInterface $message);

    /**
     * Unserialize a message.
     *
     * @param string $serializedMessage
     * @return mixed
     */
    public function unserialize($serializedMessage);

}