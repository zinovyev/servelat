<?php


namespace Servelat\Components\Daemonizer;


use Servelat\Base\AbstractApplication;
use Servelat\Base\ComponentInterface;

/**
 * Class DaemonizerComponent.
 * The daemonizer class component.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class DaemonizerComponent implements ComponentInterface
{
    /**
     * Get the name of the component.
     * Used to register unique component.
     *
     * @return string
     */
    public function getName()
    {
        return 'daemonizer';
    }

    /**
     * Register component.
     * Component can access application configuration, dispatcher and
     *
     * @param AbstractApplication $application
     * @return mixed
     */
    public function register(AbstractApplication $application)
    {
        $application->getContainer()['daemonizer.daemonizer'] = function () {
            $pidFile = '/tmp/servelat.pid';
            return new Daemonizer($pidFile);
        };
    }
}