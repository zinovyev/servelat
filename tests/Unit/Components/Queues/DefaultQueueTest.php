<?php


namespace Servelat\Tests\Unit\Components\Queues;


use Servelat\Tests\Bootstrap\ServerApplicationAwareTestCase;

class DefaultQueueTest extends ServerApplicationAwareTestCase
{
    public function testInstanceOfSplQueue()
    {
        $this->assertInstanceOf('\SplQueue', $this->app->getContainer()['queues.default_queue']);
    }
}
