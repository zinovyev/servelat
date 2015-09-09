<?php


namespace servelat\base;

use Pimple\ServiceProviderInterface;

/**
 * Interface ComponentInterface.
 * Interface ComponentInterface is a bootstrap for building servelat components.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface ServiceInterface extends ServiceProviderInterface
{
    /**
     * Get the array of values that customizes the provider.
     * These parameters will be registered in the global space of the container.
     *
     * @return array
     */
    public function getGlobals();
}