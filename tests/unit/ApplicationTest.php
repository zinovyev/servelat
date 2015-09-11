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
        $this->assertInstanceOf('\servelat\Application', $this->app->container['app']);
        $this->assertTrue(isset($this->app->container['app']));
        $this->assertFalse(isset($this->app->container['foo']));
        $this->assertEquals('app', $this->app->container->keys()[0]);
    }

    /**
     */
    public function testTryToRegisterRegisteredService()
    {
        $this->app->container['app']->registerService($this->app);
    }
}