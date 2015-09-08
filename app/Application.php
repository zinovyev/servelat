<?php

namespace servelat;

use servelat\exceptions\ServelatException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Pimple\Container;

/**
 * Class Application.
 * Class that keeps container, dispatcher and properties together.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class Application
{
    /**
     * @var array
     */
    protected $parameters;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatcher;

    /**
     * @var \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected $resolver;

    /**
     * @var \Pimple\Container
     */
    protected $container;

    /**
     * @param string $property Property name
     * @return array|Container|EventDispatcher
     * @throws ServelatException
     */
    public function __get($property)
    {
        if ($property === 'container') {
            return $this->container;
        } elseif ($property === 'dispatcher') {
            return $this->dispatcher;
        } elseif ($property === 'parameters') {
            return $this->parameters;
        } else {
            throw new ServelatException(sprintf(
                'Either the property %s does not exist or access is denied.',
                $property
            ));
        }
    }

    /**
     * Configure application.
     *
     * @param array $parameters
     * @return $this
     */
    public function configure(array $parameters = [])
    {
        if ([] !== $parameters) {
            $this->parameters = $this->getResolver()->resolve($parameters);
        }

        return $this;
    }

    /**
     * Get service
     *
     * @param string $service
     * @return mixed
     * @throws ServelatException
     */
    public function get($service)
    {
        try {
            return $this->container[$service];
        } catch (\Exception $ex) {
            throw new ServelatException(sprintf(
                'Service %s was not found.',
                $service
            ));
        }
    }

    /**
     * @param bool|false $reload
     * @return EventDispatcher
     */
    public function getDispatcher($reload = false)
    {
        if (null === $this->dispatcher || $reload) {
            $this->dispatcher = new EventDispatcher();
            $this->configureListeners($this->dispatcher);
        }

        return $this->dispatcher;
    }

    /**
     * @param bool|false $reload
     * @return OptionsResolver
     */
    public function getResolver($reload = false)
    {
        if (null === $this->resolver || $reload) {
            $this->resolver = new OptionsResolver();
            $this->configureProperties($this->resolver);
        }

        return $this->resolver;
    }

    /**
     * @param bool|false $reload
     * @return Container
     */
    public function getContainer($reload = false)
    {
        if (null === $this->container || $reload) {
            $this->container = new Container();
            $this->configureServices($this->container);
        }

        return $this->container;
    }
}