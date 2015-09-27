<?php


namespace Servelat\Tests\Unit\Components\ProcessManager;


use Servelat\Components\ProcessManager\Processes\EmptyProcess;
use Servelat\Components\ProcessManager\ProcessManager;
use Servelat\Components\TaskManager\Handlers\Idle\EmptyTask;
use Servelat\Tests\Bootstrap\ServerApplicationAwareTestCase;

class ProcessManagerTest extends ServerApplicationAwareTestCase
{
    public function testAddProcessesToTheProcessManager()
    {
        /** @var ProcessManager $processManager */
        $processManager = $this->app->getContainer()['process_manager'];

        // Collect processes
        $processes = [];
        for ($i = 0, $n = 3; $i < $n; ++$i) {
            $emptyProcess = $this->getMockBuilder('\Servelat\Components\ProcessManager\Processes\EmptyProcess')
                ->disableOriginalConstructor()
                ->setMethods(null)
                ->getMock();
            $processes[] = $emptyProcess;
        }

        // Add processes
        $processManager->addProcess($processes[0]);
        $this->assertEquals(1, $processManager->countProcesses());
        $processManager->addProcess($processes[1]);
        $this->assertEquals(2, $processManager->countProcesses());
        $this->assertEquals(0, $processes[1]->getExitCode());
        $processManager->addProcess($processes[2]);
        $this->assertEquals(3, $processManager->countProcesses());

        return [$processManager, $processes];
    }

    /**
     * @depends testAddProcessesToTheProcessManager
     */
    public function testRunStreamSelect(array $stack)
    {
        $processManager = $stack[0];

        // Run stream select
        $num = $processManager->streamSelect();
        $this->assertEquals(3, $num);

        return $stack;
    }

    /**
     * @depends testRunStreamSelect
     */
    public function testAfterSelectProcessManagerStatus(array $stack)
    {
        $processManager = $stack[0];
        $processes = $stack[1];

        // Check than all processes are closed
        $this->assertEquals(0, $processManager->countProcesses());
        $this->assertEquals(0, $processes[1]->getExitCode());
        $this->assertEquals(true, $processes[0]->isClosed());

    }
}