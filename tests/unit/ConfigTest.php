<?php

use EFrane\Letterpress\Config;

class ConfigTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $configPath = 'config';

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
        Config::init($this->configPath);

        $this->assertEquals('en_GB', Config::get('letterpress.locale'));
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
        Config::init($this->configPath);
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

    public function testHas() {
        Config::init($this->configPath);

        $this->assertTrue(Config::has('letterpress.locale'));
        $this->assertFalse(Config::has('letterpress.non-existent-value'));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Config must be initialized before usage.
     **/
    public function testHasWithoutInit()
    {
        Config::reset(true);
        Config::has('letterpress.locale');
    }

    public function testApply()
    {
        Config::init($this->configPath);
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
        Config::init($this->configPath);

        Config::apply(['testvalue']);
    }
}
