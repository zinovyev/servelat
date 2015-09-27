<?php


namespace Servelat\Tests\Unit\Base;

use Servelat\Tests\Fixtures\Application;
use Servelat\Tests\Fixtures\Component;

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

    public function testRegisterNewComponent()
    {
        $this->app->addComponent(new Component());
        $this->assertTrue($this->app->hasComponent('fixture'));

        return [$this->app];
    }

    /**
     * @depends testRegisterNewComponent
     * @expectedException \Servelat\Base\Exceptions\InvalidArgumentException
     * @expectedExceptionMessage Component fixture is already registered
     */
    public function testExceptionOnDuplicateComponentRegistration(array $stack)
    {
        $app = $stack[0];
        $app->addComponent(new Component());
    }
}
