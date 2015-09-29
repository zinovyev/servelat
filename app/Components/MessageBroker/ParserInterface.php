<?php


namespace Servelat\Components\MessageBroker;

/**
 * Interface ParserInterface.
 * Used to parse serialized broker messages.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface ParserInterface
{
    /**
     * Check if parser is suitable for the message.
     *
     * @param \Servelat\Components\MessageBroker\MessageInterface $message
     * @return bool
     */
    public function isSuitableFor(MessageInterface $message);

    /**
     * Parse message and get the payload.
     *
     * @param \Servelat\Components\MessageBroker\MessageInterface $message
     * @return mixed
     */
    public function parse(MessageInterface $message);
}