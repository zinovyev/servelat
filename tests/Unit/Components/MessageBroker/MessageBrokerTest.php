<?php


namespace Servelat\Tests\Unit\Components\MessageBroker;


use Servelat\Components\MessageBroker\MessageBroker;
use Servelat\Components\MessageBroker\Messages\JsonMessage;
use Servelat\Components\MessageBroker\Serializers\JsonMessageSerializer;
use Servelat\Components\ProcessHandlers\FlatPhp\Task;
use Servelat\Components\ProcessManager\ProcessManager;
use Servelat\Components\TaskManager\TaskManager;
use Servelat\Tests\Bootstrap\ServerApplicationAwareTestCase;

class MessageBrokerTest extends ServerApplicationAwareTestCase
{
    /**
     * @var MessageBroker
     */
    protected $messageBroker;

    public function setUp()
    {
        parent::setUp();
        $this->messageBroker = $this->app->getContainer()['message_broker.message_broker'];
    }

    public function testRouteRequestMessage()
    {
        $message = '#json#{"rkey":"task_manager","payload":"Tzo0ODoiU2VydmVsYXRcQ29tcG9uZW50c1xQcm9jZXNzSGFuZGxlcnNcRmxhdFBocFxUYXNrIjo1OntzOjU6IgAqAGlkIjtpOjE0NDM2NDM1MTE7czoxMToiACoAb3duZXJQaWQiO2k6MDtzOjEwOiIAKgB0aW1lb3V0IjtpOjA7czoyMDoiACoAbnVtYmVyT2ZJbnN0YW5jZXMiO047czoxMDoiACoAcGF5bG9hZCI7czoxMToiZWNobyAiYmF6IjsiO30="}';
        $this->messageBroker->routeRequestMessage($message);

        /** @var TaskManager $taskManager */
        $taskManager = $this->app->getContainer()['task_manager.task_manager'];
        $this->assertEquals(1, $taskManager->countTasks());

        /** @var ProcessManager $processManager */
        $processManager = $this->app->getContainer()['process_manager.process_manager'];
        $processManager->streamSelect();
    }
}
