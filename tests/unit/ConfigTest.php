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
    public function testGetWithoutInit()
    {
        Config::reset(true); // make sure, Config is not initialized
        Config::get('letterpress.locale');
    }

    public function testSet()
    {
        Config::init('../config');
        Config::set('letterpress.testvalue', 'value');

        $this->assertEquals('value', Config::get('letterpress.testvalue'));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Config must be initialized before usage.
     **/
    public function testSetWithoutInit()
    {
        Config::reset(true);
        Config::set('letterpress.testvalue', 'value');

        $this->assertEquals('value', Config::get('letterpress.testvalue'));
    }

    public function testApply()
    {
        Config::init('../config');
        Config::apply(['foo' => 'bar', 'fizz.baz' => 'foobar']);

        $this->assertEquals('bar', Config::get('foo'));
        $this->assertEquals('foobar', Config::get('fizz.baz'));
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Identifier must be string.
     **/
    public function testApplyWithNonStringKey()
    {
        Config::init('../config');

        Config::apply(['testvalue']);
    }
}
