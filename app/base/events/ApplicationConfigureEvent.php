<?php


namespace servelat\base\events;


use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ConfigurationEvent.
 * Event thrown on application configuration.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class ApplicationConfigureEvent extends Event
{
    /**
     * @var OptionsResolver
     */
    protected $resolver;

    /**
     * @param OptionsResolver $resolver
     */
    public function __construct(OptionsResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @return OptionsResolver
     */
    public function getResolver()
    {
        return $this->resolver;
    }
}