<?php


namespace servelat;

use servelat\base\AbstractApplication;
use servelat\base\exceptions\BadConfigurationException;
use Symfony\Component\OptionsResolver\Options;
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
     * Applciation version
     *
     * @const string
     */
    const VERSION = '0.01';

    /**
     * Applciation runtime dir
     *
     * @const string
     */
    const DEFAULT_RUNTIME_DIR = '/tmp/servelat';

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
        $resolver
            ->setDefault('version', self::VERSION)->setAllowedTypes('version', 'string')
            ->setDefault('vendor_dir', $this->getVendorDir())->setAllowedTypes('vendor_dir', 'string')
            ->setDefault('base_dir', $this->getBaseDir())->setAllowedTypes('base_dir', 'string')
            ->setDefault('application_dir', function (Options $options) {
                return dirname($options['vendor_dir']);
            })->setAllowedTypes('application_dir', 'string')
            ->setDefault('runtime_dir', self::DEFAULT_RUNTIME_DIR)->setAllowedTypes('runtime_dir', 'string')
            ->setDefault('bin_file', function (Options $options) {
                return $this->getBinFile($options['vendor_dir']);
            })->setAllowedTypes('bin_file', 'string')
        ;
    }

    /**
     * Configure built in default services.
     *
     * @return $this
     * @throws base\exceptions\BadConfigurationException
     */
    protected function configureServices()
    {
        $this->registerServices([]);

        return $this;
    }

    /**
     * Get path of the vendor directory.
     *
     * @return string
     * @throws BadConfigurationException
     */
    protected function getVendorDir()
    {
        if (
            getenv('COMPOSER_VENDOR_DIR')
            && is_dir(__DIR__ . '/../' . getenv('COMPOSER_VENDOR_DIR'))
        ) {
            return realpath(__DIR__ . '/../' . getenv('COMPOSER_VENDOR_DIR'));
        } elseif (is_file(__DIR__ . '/../vendor/autoload.php')) {
            return realpath(__DIR__ . '/../vendor');
        } elseif (is_file(__DIR__ . '/../../../autoload.php')) {
            return realpath(__DIR__ . '/../../../');
        } else {
            throw new BadConfigurationException(
                'Could not correctly determinate location of the vendor directory.'
            );
        }
    }

    /**
     * Get servelat application base directory.
     *
     * @return string
     */
    protected function getBaseDir()
    {
        return realpath(__DIR__ . '/..');
    }

    /**
     * Get path of the bin directory.
     *
     * @param $vendorDir
     * @return string
     * @throws BadConfigurationException
     */
    protected function getBinFile($vendorDir)
    {
        if ($vendorDir && is_dir($vendorDir) && is_file($vendorDir . '/bin/servelat.php')) {
            return realpath($vendorDir . '/bin/servelat.php');
        } elseif (is_file(__DIR__ . '/../bin/servelat.php')) {
            return realpath(__DIR__ . '/../bin/servelat.php');
        } else {
            throw new BadConfigurationException(
                'Could not correctly determinate location of the bin directory.'
            );
        }
    }
}