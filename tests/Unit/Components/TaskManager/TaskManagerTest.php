<?php


namespace Servelat\Tests\Unit\Components;


use Servelat\Components\TaskManager\Handlers\Idle\EmptyTask;
use Servelat\Tests\Bootstrap\ServerApplicationAwareTestCase;
use Servelat\Components\TaskManager\TaskManager;

class TaskManagerTest extends ServerApplicationAwareTestCase
{
    public function testAddNextTask()
    {
        // Create task manager
        /** @var \Servelat\Components\TaskManager\TaskManager $taskManager */
        $taskManager = $this->app->getContainer()['task_manager.task_manager'];
        $this->assertInstanceOf('\Servelat\Components\TaskManager\TaskManager', $taskManager);

        // Add three tasks
        $taskManager->addTask(new EmptyTask());
        $this->assertEquals(1, $taskManager->countTasks());
        $taskManager->addTask(new EmptyTask());
        $this->assertEquals(2, $taskManager->countTasks());
        $taskManager->addTask(new EmptyTask());
        $this->assertEquals(3, $taskManager->countTasks());

        // Handle and count
        $taskManager->handleNext();
        $this->assertEquals(2, $taskManager->countTasks());
        $taskManager->handleNext();
        $this->assertEquals(1, $taskManager->countTasks());
        $taskManager->handleNext();
        $this->assertEquals(0, $taskManager->countTasks());
        $taskManager->handleNext();
        $this->assertEquals(0, $taskManager->countTasks());
    }
}