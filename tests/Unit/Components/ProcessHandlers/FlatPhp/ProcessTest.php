<?php


namespace Servelat\Tests\Unit\Components\ProcessHandlers\FlatPhp;


use Servelat\Components\ProcessHandlers\FlatPhp\Process;
use Servelat\Components\ProcessHandlers\FlatPhp\Task;

class ProcessTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Process
     */
    protected $process;

    public function setUp()
    {
        $task = new Task();
        $this->process = new Process($task);
    }

    /**
     * @expectedException \Servelat\Base\Exceptions\InvalidArgumentException
     */
    public function testWrongResourceTypeCausesToException()
    {
        $resource = 'foo';
        $this->process->setResource($resource);
    }

    public function testSetResource()
    {
        $resource = tmpfile();
        $this->process->setResource($resource);
    }

    /**
     * @expectedException \Servelat\Base\Exceptions\InvalidArgumentException
     */
    public function testWrongStreamTypeCausesToException()
    {
        $streams = ['foo', 1, null];
        $this->process->setStreams($streams);
    }

    public function testSetStream()
    {
        $streams = [
            fopen('/dev/null', 'r'),
            fopen('/dev/null', 'w'),
            fopen('/dev/null', 'w'),
        ];
        $this->process->setStreams($streams);
        $this->assertEquals($streams, $this->process->getStreams());
    }
}
