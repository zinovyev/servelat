<?php


namespace Servelat\Components\MessageBroker;

/**
 * Interface MessageInterface.
 * Message representation.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface MessageInterface extends \Serializable
{
    /**
     * @param mixed $payload Any abstract value
     */
    public function __construct($payload);
}