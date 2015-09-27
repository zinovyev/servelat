<?php


namespace Servelat\Base;

use Pimple\Container as Pimple;
use Pimple\ServiceProviderInterface;
use Servelat\Base\Exceptions\InvalidArgumentException;

/**
 * Class Container.
 * Servelat base container.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class Container extends Pimple
{
    /**
     * Application service id
     *
     * @const string
     */
    const APPLICATION_ID = 'app';

    /**
     * @var AbstractApplication
     */
    protected $application;

    /**
     * Instantiate the container.
     *
     * Objects and parameters can be passed as argument to the constructor.
     *
     * @param AbstractApplication $application
     */
    public function __construct(AbstractApplication $application)
    {
        $this->application = $application;
        parent::__construct([]);
    }

    /**
     * Sets a parameter or an object.
     *
     * Objects must be defined as Closures.
     *
     * Allowing any PHP callable leads to difficult to debug problems
     * as function names (strings) are callable (creating a function with
     * the same name as an existing parameter would break your container).
     *
     * @param  string $id The unique identifier for the parameter or object
     * @param  mixed $value The value of the parameter or a closure to define an object
     * @throws \RuntimeException Prevent override of a frozen service
     * @throws InvalidArgumentException Prevent override of application service
     */
    public function offsetSet($id, $value)
    {
        if (self::APPLICATION_ID === $id) {
            throw new InvalidArgumentException("Cannot override base application service.");
        }

        parent::offsetSet($id, $value);
    }

    /**
     * Gets a parameter or an object.
     *
     * @param string $id The unique identifier for the parameter or object
     *
     * @return mixed The value of the parameter or an object
     *
     * @throws \InvalidArgumentException if the identifier is not defined
     */
    public function offsetGet($id)
    {
        if (self::APPLICATION_ID === $id) {
            return $this->application;
        }

        return parent::offsetGet($id);
    }

    /**
     * Registers a service provider.
     *
     * @param ServiceProviderInterface $provider A ServiceProviderInterface instance
     * @param array $values An array of values that customizes the provider (never used)
     *
     * @return static
     */
    public function register(ServiceProviderInterface $provider, array $values = [])
    {
        return parent::register($provider, []);
    }
}