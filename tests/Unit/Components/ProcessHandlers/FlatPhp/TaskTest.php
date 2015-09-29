<?php


namespace Servelat\Tests\Unit\Components\ProcessHandlers\FlatPhp;


use Servelat\Components\ProcessHandlers\FlatPhp\Task;

class TaskTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleAssignments()
    {
        $task = new Task();

        $task
            ->setId(1024)
            ->setNumberOfInstances(2)
            ->setOwnerPid(512)
            ->setPayload('echo "foo";')
            ->setTimeout(4)
        ;

        $this->assertEquals(1024, $task->getId());
        $this->assertEquals(2, $task->getNumberOfInstances());
        $this->assertEquals(512, $task->getOwnerPid());
        $this->assertEquals('echo "foo";', $task->getPayload());
        $this->assertEquals(4, $task->getTimeout());
    }
}
