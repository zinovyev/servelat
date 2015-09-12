<?php


namespace servelat;

/**
 * Class ServelatEvents.
 * Dictionary class used to keep events.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
final class ServelatEvents
{
    /**
     * The application.configure event is  thrown each time
     * the application is configured.
     *
     * The event listener receives an
     * servelat\base\events\ConfigureApplicationEvent instance.
     *
     * @const string
     */
    const APPLICATION_CONFIGURE = 'application.configure';
}