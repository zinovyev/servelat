<?php


namespace Servelat\Tests\Unit\Components\ProcessHandlers\FlatPhp;


use Servelat\Components\ProcessHandlers\FlatPhp\Task;
use Servelat\Components\ProcessHandlers\FlatPhp\TaskHandler;
use Servelat\Components\TaskManager\Handlers\Idle\EmptyTask;

class TaskHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testIsSuitableForFlatPhpTask()
    {
        $handler = new TaskHandler();
        $flatPhpTask = new Task();

        $this->assertTrue($handler->isSuitableFor($flatPhpTask));
    }

    public function testIsNotSuitableForEmptyTask()
    {
        $handler = new TaskHandler();
        $emptyTask = new EmptyTask();

        $this->assertFalse($handler->isSuitableFor($emptyTask));
    }
}
