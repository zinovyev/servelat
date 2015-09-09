<?php


namespace servelat;

use servelat\base\AbstractApplication;

class Application extends AbstractApplication
{
    public function __construct()
    {
        parent::__construct();
        $this->registerBaseServices();
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
}