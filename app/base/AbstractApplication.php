<?php


namespace servelat\base;

use Pimple\Container;
use servelat\base\exceptions\ApplicationConfigurationException;
use servelat\base\exceptions\ServiceNotFoundException;
use servelat\base\exceptions\ServiceOverrideException;

/**
 * Class Application.
 * Base servelat application.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
abstract class AbstractApplication implements  ServiceInterface
{
    /**
     * @const Id for basic application service
     */
    const APPLICATION_SERVICE_ID = 'app';

    /**
     * @var \Pimple\Container
     */
    protected $container;

    /**
     * Do not override parent::__contruct() method!
     */
    public function __construct()
    {
        // Self register the application
        $this->getContainer()->register($this);
    }

    /**
     * Check if the given service or property is defined.
     *
     * @param string $id The unique identifier for the parameter or object
     * @return bool
     */
    public function has($id)
    {
        return isset($this->container[$id]);
    }

    /**
     * Get service or property by id.
     *
     * @param string $id The unique identifier for the parameter or object
     * @return mixed
     * @throws ServiceNotFoundException
     */
    public function get($id)
    {
        try {
            return $this->container[$id];
        } catch (\InvalidArgumentException $ex) {
            throw new ServiceNotFoundException(sprintf(
                'Service or property named %s was not found for. %s',
                $id,
                $ex->getMessage()
            ), $ex->getCode(), $ex);
        }
    }

    /**
     * Show registered services ids.
     *
     * @return array
     */
    public function show()
    {
        return $this->container->keys();
    }

    /**
     * Register single service in the di container.
     *
     * @param ServiceInterface $service
     * @return $this
     * @throws ApplicationConfigurationException
     * @throws ServiceOverrideException
     */
    public function registerService(ServiceInterface $service)
    {
        $globals = $service->getGlobals();
        if (null === $globals || !is_array($globals)) {
            throw new ApplicationConfigurationException(
                'The value returned by the ServiceInterface::getGlobals() method must be of type array only.'
            );
        }

        try {
            $this->getContainer()->register(
                $service,
                $service->getGlobals()
            );
        } catch (\RuntimeException $ex) {
            throw new ServiceOverrideException(sprintf(
                'Duplicate service id. %s',
                $ex->getMessage()
            ), $ex->getCode(), $ex);
        }

        return $this;
    }

    /**
     * Register a scope of services in the di container.
     *
     * @param ServiceInterface[] $services
     * @return $this
     * @throws ApplicationConfigurationException
     */
    public function registerServices(array $services)
    {
        foreach ($services as $service) {
            if (!($service instanceof ServiceInterface)) {
                throw new ApplicationConfigurationException('Can not register service. Service must implement ServiceInterface.');
            }

            // Register service in the container
            $this->registerService($service);
        }

        return $this;
    }

    /**
     * Get the container instance.
     *
     * @param bool|false $reload
     * @return Container
     */
    protected function getContainer($reload = false)
    {
        if (null === $this->container || $reload) {
            $this->container = new Container();
        }

        return $this->container;
    }

    /**
     * Registers services on the given container.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Container $pimple A container instance
     */
    public function register(Container $pimple)
    {
        $self = $this;
        $pimple[self::APPLICATION_SERVICE_ID] = function () use ($self) {
            return $self;
        };
    }

    /**
     * Get the array of values that customizes the provider.
     * These parameters will be registered in the global space of the container.
     *
     * @return array
     */
    abstract public function getGlobals();
}