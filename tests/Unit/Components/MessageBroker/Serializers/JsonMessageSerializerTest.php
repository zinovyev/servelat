<?php


namespace Servelat\Tests\Unit\Components\MessageBroker\Serializers;


use Servelat\Components\MessageBroker\Messages\JsonMessage;
use Servelat\Components\MessageBroker\Serializers\JsonMessageSerializer;

class JsonMessageSerializerTest extends \PHPUnit_Framework_TestCase
{
    public function testSerializeJsonMessage()
    {
        $jsonMessage = new JsonMessage('task_manager', 'echo "foo";');
        $jsonSerializer = new JsonMessageSerializer();

        $this->assertTrue($jsonSerializer->canSerialize($jsonMessage));
        $serialized = $jsonSerializer->seriazlie($jsonMessage);
        $this->assertEquals('#json#{"rkey":"task_manager","payload":"echo \"foo\";"}', $serialized);

        return [$serialized];
    }

    /**
     * @param array $stack
     * @depends testSerializeJsonMessage
     */
    public function testUnserializeMessage(array $stack)
    {
        $serialized = $stack[0];
        $jsonSerializer = new JsonMessageSerializer();
        $this->assertTrue($jsonSerializer->canUnserialize($serialized));
        $jsonMessage = $jsonSerializer->unserialize($serialized);

        $this->assertInstanceOf('\Servelat\Components\MessageBroker\Messages\JsonMessage', $jsonMessage);
        $this->assertEquals('task_manager', $jsonMessage->getRoutingKey());
        $this->assertEquals('echo "foo";', $jsonMessage->getPayload());
    }
}
