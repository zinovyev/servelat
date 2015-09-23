<?php


namespace Servelat;


use Servelat\Base\AbstractApplication;
use Servelat\Components\TaskManager\Queues\QueuesComponent;
use Servelat\Components\TaskManager\TaskManagerComponent;

/**
 * Class ServerApplication.
 * Servelat server application.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class ServerApplication extends AbstractApplication
{
    /**
     * @param array $configuration Configuration array
     */
    public function __construct(array $configuration = [])
    {
        parent::__construct($configuration);
        $this->registerComponents();
    }

    /**
     * Register components.
     *
     * @return $this
     */
    protected function registerComponents()
    {
        $this->setComponents([
            new TaskManagerComponent(),
            new QueuesComponent(),
        ]);

        return $this;
    }

}