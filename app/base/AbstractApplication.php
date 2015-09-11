<?php


namespace servelat\base;

use Pimple\Container;
use servelat\base\events\ApplicationConfigureEvent;
use servelat\base\events\ConfigureApplicationEvent;
use servelat\base\exceptions\BadConfigurationException;
use servelat\base\exceptions\UnknownPropertyException;
use servelat\ServelatEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Application.
 * Base servelat application.
 *
 * @property \Pimple\Container $container
 * @property \ArrayObject $parameters
 * @property \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
abstract class AbstractApplication implements  ServiceInterface
{
    /**
     * Applciation Id
     *
     * @const string
     */
    const APPLICATION_ID = 'app';

    /**
     * @var \Pimple\Container
     */
    protected $containerInstance;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatcherInstance;

    /**
     * @var \Symfony\Component\OptionsResolver\OptionsResolver
     */
    protected $resolverInstance;

    /**
     * @var \ArrayObject
     */
    protected $parametersStorage;

    /**
     * @var ServiceInterface[]
     */
    protected $services;

    /**
     * @var boolean
     */
    protected $configured = false;

    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->registerService($this);
        $this->configure($parameters);
    }

    /**
     * @param $property
     * @return \ArrayObject|Container|EventDispatcher
     * @throws BadConfigurationException
     * @throws UnknownPropertyException
     */
    public function __get($property)
    {
        if ($property === 'container') {
            return $this->getContainer();
        } elseif ($property === 'parameters') {
            return $this->getParameters();
        } elseif ($property === 'dispatcher') {
            return $this->getDispatcher();
        } else {
            throw new UnknownPropertyException(sprintf(
                'Property %s is unknown.',
                $property
            ));
        }
    }

    /**
     * Configure application.
     * Resolve application parameters.
     *
     * @param array $parameters
     * @param bool $reload
     * @return $this
     */
    public function configure(array $parameters, $reload = false)
    {
        if ((false === $this->configured || true === $reload) && [] !== $parameters) {
            // Configure every single service
            $resolver = $this->getResolver($reload);
            foreach ($this->services as $service) {
                $service->configureParameters($resolver);
            }
            $resolvedParameters = $resolver->resolve($parameters);
            $this->parametersStorage = new \ArrayObject($resolvedParameters);

            // Dispatch an event
            $event = new ApplicationConfigureEvent($resolver);
            $this->getDispatcher()->dispatch(ServelatEvents::APPLICATION_CONFIGURE, $event);

            $this->configured = true;
        }

        return $this;
    }

    /**
     * @return \ArrayObject
     * @throws BadConfigurationException
     */
    public function getParameters()
    {
        if (null === $this->parametersStorage) {
            throw new BadConfigurationException('Application is not yet configured.');
        }

        return $this->parametersStorage;
    }

    /**
     * @param boolean $reload
     * @return Container
     */
    public function getContainer($reload = false)
    {
        if (null === $this->containerInstance || true === $reload) {
            $this->containerInstance = new Container();
        }

        return $this->containerInstance;
    }

    /**
     * @param bool $reload
     * @return EventDispatcher
     */
    public function getDispatcher($reload = false)
    {
        if (null === $this->dispatcherInstance || true !== $reload) {
            $this->dispatcherInstance = new EventDispatcher();
        }

        return $this->dispatcherInstance;
    }

    /**
     * Register new service.
     *
     * @param ServiceInterface $service
     * @return $this
     */
    public function registerService(ServiceInterface $service)
    {
        $this->services[] =  $service;
        $this->getContainer()->register($service);

        return $this;
    }

    /**
     * Register a scope of services.
     *
     * @param array $services
     * @return $this
     * @throws BadConfigurationException
     */
    public function registerServices(array $services = [])
    {
        foreach ($services as $service) {
            if (!($service instanceof ServiceInterface)) {
                throw new BadConfigurationException('Service must be an instance of \servelat\base\ServiceInterface');
            }

            $this->registerService($service);
        }

        return $this;
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
        $pimple[self::APPLICATION_ID] = $this;
    }


    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return [];
    }

    /**
     * @param bool|false $reload
     * @return OptionsResolver
     */
    protected function getResolver($reload = false)
    {
        if (null === $this->resolverInstance || true === $reload) {
            $this->resolverInstance = new OptionsResolver();
        }

        return $this->resolverInstance;
    }
}