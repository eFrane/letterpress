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
        $this->assertInstanceOf('EFrane\Letterpress\Config', Config::init());
    }

    public function testGet()
    {
        Config::init('../config');

        $this->assertEquals(['EnglishQuotes'], Config::get('jolitypo.en_GB'));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Config must be initialized before usage.
     **/
    public function testReset()
    {
        Config::reset();
        Config::get('letterpress.locale');
    }
}
