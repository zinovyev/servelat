<?php


namespace servelat\tests\bootstrap;


use servelat\Application;

/**
 * Test ApplicationAwareTestCase.
 * Base test for testing services of the application.
 *
 * @author Ivan Zinovyev <vanyazin@gmail.com>
 */
class ApplicationAwareTestCase extends \PHPUnit_Framework_TestCase
{
    protected $app;

    public function setUp()
    {
        $this->app = new Application();
    }
}
