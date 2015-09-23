<?php


namespace Servelat\Base;

use Servelat\Base\Exceptions\InvalidArgumentException;
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
     * @var array
     */
    protected $registeredComponents = [];

    /**
     * @param array $configuration Configuration array
     */
    public function __construct(array $configuration = [])
    {
        if ([] !== $configuration) {
            $this->configure($configuration);
        }
    }

    /**
     * Configure application
     *
     * @param array $configuration Configuration array
     * @return $this
     */
    public function configure(array $configuration = [])
    {
        $this->getConfiguration()->exchangeArray($configuration);

        return $this;
    }

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

    /**
     * Check if component is registered or not.
     *
     * @param string $componentName The name of the component.
     * @return bool
     */
    public function hasComponent($componentName)
    {
        return isset($this->registeredComponents[$componentName]);
    }

    /**
     * Register new component.
     *
     * @param ComponentInterface $component Component instance
     * @return $this
     * @throws InvalidArgumentException
     */
    public function addComponent(ComponentInterface $component)
    {
        if ($this->hasComponent($component->getName())) {
            throw new InvalidArgumentException(sprintf(
                'Component %s is already registered',
                $component->getName()
            ));
        }

        $this->registeredComponents[$component->getName()] = true;
        $component->register($this);

        return $this;
    }

    /**
     * Register set of components.
     *
     * @param array $components
     * @return $this
     * @throws InvalidArgumentException
     */
    public function setComponents(array $components)
    {
        foreach ($components as $component) {
            $this->addComponent($component);
        }

        return $this;
    }
}