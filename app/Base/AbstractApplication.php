<?php


namespace Servelat\Base;

use Symfony\Component\EventDispatcher\EventDispatcher;
use ArrayObject;

/**
 * Class AbstractApplication.
 * Base Servelat application structure.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
abstract class AbstractApplication
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var \ArrayObject
     */
    protected $configuration;

    /**
     * @return Container
     */
    public function getContainer()
    {
        if (null === $this->container) {
            $this->container = new Container($this);
        }

        return $this->container;
    }

    /**
     * @return EventDispatcher
     */
    public function getDispatcher()
    {
        if (null === $this->dispatcher) {
            $this->dispatcher = new EventDispatcher();
        }

        return $this->dispatcher;
    }

    /**
     * @return ArrayObject
     */
    public function getConfiguration()
    {
        if (null === $this->configuration) {
            $this->configuration = new ArrayObject();
        }

        return $this->configuration;
    }
}