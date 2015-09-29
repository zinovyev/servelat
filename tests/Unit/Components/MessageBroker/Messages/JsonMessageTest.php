<?php


namespace Servelat\Tests\Unit\Components\MessageBroker\Messages;


use Servelat\Components\MessageBroker\Messages\JsonMessage;

class JsonMessageTest extends \PHPUnit_Framework_TestCase
{
    public function testJsonMessageCreateAndReceiveProperties()
    {
        $jsonMessage = new JsonMessage('task_manager', 'echo "foo";');
        $this->assertEquals('task_manager', $jsonMessage->getRoutingKey());
        $this->assertEquals('echo "foo";', $jsonMessage->getPayload());
    }
}
