<?php

use EFrane\Letterpress\Config;

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
        $this->assertInstanceOf('EFrane\Letterpress\Config', Config::init('./config'));
    }

    public function testGet()
    {
        Config::init('./config');

        $this->assertEquals(['EnglishQuotes'], Config::get('jolitypo.en_GB'));
    }
}
