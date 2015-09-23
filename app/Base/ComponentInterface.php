<?php


namespace Servelat\Base;

/**
 * Interface ComponentInterface.
 * Servelat Component interface.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface ComponentInterface
{
    /**
     * Get the name of the component.
     * Used to register unique component.
     *
     * @return string
     */
    public function getName();

    /**
     * Register component.
     * Component can access application configuration, dispatcher and
     *
     * @param AbstractApplication $application
     * @return mixed
     */
    public function register(AbstractApplication $application);
}