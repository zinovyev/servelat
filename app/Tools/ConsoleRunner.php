<?php


namespace Servelat\Tools;

use Servelat\Tools\Commands\ServelatServerStartCommand;
use Symfony\Component\Console\Application as SymfonyConsole;

/**
 * Class ConsoleRunner.
 * The Symfony console runner tool for servelat application.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class ConsoleRunner
{
    /**
     * Application version.
     *
     * @const string
     */
    const VERSION = '0.2';

    /**
     * Application description.
     *
     * @const string
     */
    const NAME = 'Servelat Command Line Interface';

    /**
     * Run the console instance.
     *
     * @return int
     * @throws \Exception
     */
    public static function run()
    {
        $console = self::createConsoleInstance();
        return $console->run();
    }

    /**
     * Create new console instance.
     *
     * @return \Symfony\Component\Console\Application
     */
    public static function createConsoleInstance()
    {
        $console = new SymfonyConsole(self::NAME, self::VERSION);
        $console->addCommands([
            new ServelatServerStartCommand(),
        ]);
        return $console;
    }
}