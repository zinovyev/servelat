<?php


namespace Servelat\Components\ProcessManager;

use Servelat\Base\AbstractApplication;
use Servelat\Base\ComponentInterface;

/**
 * Class ProcessManagerComponent.
 * This component contains processes and a manager class to rule processes.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class ProcessManagerComponent implements ComponentInterface
{
    /**
     * Get the name of the component.
     * Used to register unique component.
     *
     * @return string
     */
    public function getName()
    {
        return 'process_manager';
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

    }
}