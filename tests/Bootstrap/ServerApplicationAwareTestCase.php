<?php


namespace Servelat\Tests\Bootstrap;

use Servelat\ServerApplication;

/**
 * Class ApplicationAwareTestCase.
 * Testcase for application components.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class ServerApplicationAwareTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Servelat\ServerApplication
     */
    protected $app;

    public function setUp()
    {
        $this->app = new ServerApplication();
    }

}