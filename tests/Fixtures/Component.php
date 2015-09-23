<?php


namespace Servelat\Tests\Fixtures;


use Servelat\Base\AbstractApplication;
use Servelat\Base\ComponentInterface;

/**
 * Fixture for ComponentInterface.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class Component implements  ComponentInterface
{
    /**
     * Get the name of the component.
     * Used to register unique component.
     *
     * @return string
     */
    public function getName()
    {
        return 'fixture';
    }

    /**
     * Register component.
     * Component can access application configuration, dispatcher and
     *
     * @param AbstractApplication $application
     * @return mixed
     */
    public function register(AbstractApplication $application)
    {}
}