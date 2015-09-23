<?php


namespace Servelat\Tests\Unit;

use Servelat\Tests\Fixtures\Application;

/**
 * Class AbstractApplicationTest.
 * Class AbstractApplicationTest description.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class AbstractApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Servelat\Tests\Fixtures\Application
     */
    protected $app;

    public function setUp()
    {
        $this->app = new Application();
    }

    public function testGetContainerReturnsAContainer()
    {
        $container = $this->app->getContainer();
        $this->assertInstanceOf('\Servelat\Base\Container', $container);
    }

    public function testGetDispatcherReturnsADispatcher()
    {
        $dispatcher = $this->app->getDispatcher();
        $this->assertInstanceOf('\Symfony\Component\EventDispatcher\EventDispatcher', $dispatcher);
    }

    public function testGetConfigurationReturnsConfiguration()
    {
        $configuraton = $this->app->getConfiguration();
        $this->assertInstanceOf('\ArrayObject', $configuraton);
    }
}
