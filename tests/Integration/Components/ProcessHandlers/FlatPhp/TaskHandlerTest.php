<?php


namespace Servelat\Tests\Integration\Components\ProcessHandlers\FlatPhp;

use Servelat\Components\ProcessHandlers\FlatPhp\Task;
use Servelat\Components\ProcessManager\ProcessManager;
use Servelat\Components\TaskManager\TaskManager;
use Servelat\Tests\Bootstrap\ServerApplicationAwareTestCase;

class TaskHandlerTest extends ServerApplicationAwareTestCase
{
    public function testHandleTaskAndExecuteProcess()
    {
        /** @var TaskManager $taskManager */
        $taskManager = $this->app->getContainer()['task_manager.task_manager'];

        /** @var ProcessManager $processManager */
        $processManager = $this->app->getContainer()['process_manager.process_manager'];

        /** @var Task $flatTask */
        $flatTask = $this->app->getContainer()['process_handlers.flat_php_task'];
        $flatTask->setPayload('echo foo;');

        $taskManager->addTask($flatTask);
        $this->assertEquals(1, $taskManager->countTasks());
        $taskManager->handleNext();
        $this->assertEquals(0, $taskManager->countTasks());

        $this->assertEquals(1, $processManager->countProcesses());
        sleep(1);
        $num = $processManager->streamSelect();

        $this->assertEquals(1, $num);
    }
}
