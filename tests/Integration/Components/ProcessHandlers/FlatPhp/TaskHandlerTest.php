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
        $flatTask->setPayload('sleep(5); echo foo;');

        $taskManager->addTask($flatTask);
        $taskManager->handleNext();

        var_dump( $processManager->countProcesses() );

    }
}
