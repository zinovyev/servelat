<?php


namespace servelat\base;

use Pimple\ServiceProviderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface ComponentInterface.
 * Interface ComponentInterface is a bootstrap for building servelat components.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
interface ServiceInterface extends ServiceProviderInterface, EventSubscriberInterface
{
    /**
     * Configure application parameters.
     * All parameters will be accessible via \servelat\Applciation::parameters or \servelat\Applciation::getParameters().
     *
     * @see http://symfony.com/doc/current/components/options_resolver.html
     * @param OptionsResolver $resolver
     */
    public function configureParameters(OptionsResolver $resolver);
}