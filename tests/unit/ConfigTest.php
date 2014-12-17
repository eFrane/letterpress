<?php

class ConfigTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testInit()
    {
        $this->assertInstanceOf('EFrane\Letterpress\Config', EFrane\Letterpress\Config::init('./config'));
    }
}
