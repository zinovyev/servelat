<?php


namespace servelat;

use servelat\base\AbstractApplication;
use servelat\base\exceptions\ApplicationConfigurationException;
use servelat\base\exceptions\UnknownPropertyException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Application.
 * Base servelat application.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class Application extends AbstractApplication
{
    /**
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);
        $this->configureServices();
    }


    /**
     * Configure application parameters.
     * All parameters will be accessible via \servelat\Applciation::parameters or \servelat\Applciation::getParameters().
     *
     * @see http://symfony.com/doc/current/components/options_resolver.html
     * @param OptionsResolver $resolver
     */
    public function configureParameters(OptionsResolver $resolver)
    {
        // TODO: Implement configureParameters() method.
    }

    /**
     * Configure built in default services.
     *
     * @return $this
     * @throws base\exceptions\BadConfigurationException
     */
    protected function configureServices()
    {
        $this->registerServices([

        ]);

        return $this;
    }
}