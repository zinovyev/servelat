<?php


namespace Servelat\Tests\Unit\Components\Daemonizer;


use Servelat\Components\Daemonizer\Daemonizer;

class DaemonizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Daemonizer
     */
    protected $daemonizer;

    public function setUp()
    {
        $pidFile = tempnam('/tmp', 'servelat_');
        $this->daemonizer = new Daemonizer($pidFile, true);
    }

    public function testGetActivePid()
    {
        $activePid = $this->daemonizer->getActivePid();
        $this->assertEquals(0, $activePid);
    }

    public function testDaemonizeProcess()
    {
        $activePid = $this->daemonizer->daemonize();
        $this->assertEquals($activePid, $this->daemonizer->getActivePid());
        $this->assertEquals(getmypid(), $this->daemonizer->getActivePid());
    }

    /**
     * @expectedException \Servelat\Base\Exceptions\ServelatException
     * @expectedExceptionMessage Already daemonized!
     */
    public function testExceptionOnDoubleDaemonizationTry()
    {
        $this->daemonizer->daemonize();
        $this->daemonizer->daemonize();
    }

    public function testGetCurrentlyRunningFile()
    {
        $fileName = $this->daemonizer->getThisFile();
        $this->assertEquals(__FILE__, $fileName);
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessageRegExp #Another process with PID [\d]+ is already running#
     */
    public function testSetActivePid()
    {
        $this->daemonizer->setActivePid(getmypid());
        $this->assertEquals(getmypid(), $this->daemonizer->getActivePid());
        $this->daemonizer->daemonize();
    }
}
