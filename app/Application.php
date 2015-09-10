<?php


namespace servelat;

use servelat\base\AbstractApplication;
use servelat\base\exceptions\ApplicationConfigurationException;

/**
 * Class Application.
 * Base servelat application.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class Application extends AbstractApplication
{
    /**
     * @const Application version
     */
    const VERSION = '0.01';

    /**
     * @const Servelat bin file name
     */
    const BIN_FILE = 'servelat.php';

    protected static $vendorDir;

    protected static $applciationDir;

    protected static $binDir;

    protected static $binFile;

    public function __construct()
    {
        parent::__construct();
        $this->registerBaseServices();
    }

    /**
     * Get the array of values that customizes the provider.
     * These parameters will be registered in the global space of the container.
     *
     * @return array
     */
    public function getGlobals()
    {
        return [
            'version'           => self::VERSION,
            'vendor_dir'        => $this->getVendorDir(),
            'base_dir'          => realpath(__DIR__ . '/..'),
            'application_dir'   => $this->getApplicationDir(),
            'runtime_dir'       => '/tmp',
            'bin_dir'           => $this->getBinDir(),
            'bin_file'          => $this->getBinFile(),
        ];
    }

    /**
     * Register base services.
     *
     * @throws base\exceptions\ApplicationConfigurationException
     */
    public function registerBaseServices()
    {
        $this->registerServices([

        ]);

        return $this;
    }

    /**
     * Get the vendor dir path.
     *
     * @return string
     * @throws ApplicationConfigurationException
     */
    public function getVendorDir()
    {
        if (null === self::$vendorDir) {
            if (
                getenv('COMPOSER_VENDOR_DIR')
                && is_dir(__DIR__ . '/../' . getenv('COMPOSER_VENDOR_DIR'))
            ) {
                self::$vendorDir = realpath(__DIR__ . '/../' . getenv('COMPOSER_VENDOR_DIR'));
            } elseif (is_dir(__DIR__ . '/../vendor')) {
                self::$vendorDir = realpath(__DIR__ . '/../vendor');
            } elseif (is_dir(__DIR__ . '/../../../')) {
                self::$vendorDir = realpath(__DIR__ . '/../../../');
            } else {
                throw new ApplicationConfigurationException(
                    'Could not find the vendor directory.'
                );
            }
        }

        return self::$vendorDir;

    }

    public function getApplicationDir()
    {

    }

    /**
     * Get the bin dir path.
     *
     * @return string
     * @throws ApplicationConfigurationException
     */
    public function getBinDir()
    {
        if (null === self::$binDir ) {
            if (
                getenv('COMPOSER_BIN_DIR')
                && is_dir($this->getVendorDir() . '/' . getenv('COMPOSER_BIN_DIR'))
            ) {
                self::$binDir = realpath($this->getVendorDir() . '/' . getenv('COMPOSER_BIN_DIR'));
            } elseif (is_dir($this->getVendorDir() . '/bin')) {
                self::$binDir = realpath($this->getVendorDir() . '/bin');
            } else {
                throw new ApplicationConfigurationException(
                    'Could not find the bin directory.'
                );
            }
        }

        return self::$binDir;
    }

    /**
     * Get the bin file.
     *
     * @return string
     * @throws ApplicationConfigurationException
     */
    public function getBinFile()
    {
        if (null !== self::$binFile) {
            return self::$binFile;
        } elseif (is_file($this->getBinDir() . '/' . self::BIN_FILE)) {
            self::$binFile = realpath($this->getBinDir() . '/' . self::BIN_FILE);
        } else {
            throw new ApplicationConfigurationException(
                'Servelat binary not found.'
            );
        }

        return self::$binFile;
    }

}