<?php


namespace servelat\tests\unit;

use servelat\Application;
use Symfony\Component\Yaml\Tests\A;

/**
 * Class ApplicationTest.
 * Class ApplicationTest description.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \servelat\Application
     */
    protected $app;

    public function setUp()
    {
        $this->app = new Application();
    }

    public function testContainerContainsServices()
    {
        $this->assertInstanceOf('\servelat\Application', $this->app->get('app'));
        $this->assertTrue($this->app->has('app'));
        $this->assertFalse($this->app->has('foo'));
        var_dump($this->app->show());
        $this->assertEquals('app', $this->app->show()[0]);
    }

    /**
     * @expectedException \servelat\base\exceptions\ServiceOverrideException
     */
    public function testTryToRegisterRegisteredService()
    {
        $this->app->get('app')->registerService($this->app);
    }
}