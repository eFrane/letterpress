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
        Config::init();

        $this->assertEquals('en_GB', Config::get('letterpress.locale'));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Config must be initialized before usage.
     **/
    public function testGetFailWithoutInit()
    {
        Config::reset(true); // make sure, Config is not initialized
        Config::get('letterpress.locale');
    }

    public function testSet()
    {
        Config::init();
        Config::set('letterpress.testvalue', 'value');

        $this->assertEquals('value', Config::get('letterpress.testvalue'));
    }

    public function testSetReturnsOldValue()
    {
        $firstValue = 'value';
        $newValue   = 'newValue';

        Config::set('letterpress.testValue', $firstValue);
        $oldValue = Config::set('letterpress.testValue', $newValue);

        $this->assertEquals($firstValue, $oldValue);
        $this->assertEquals($newValue, Config::get('letterpress.testValue'));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Config must be initialized before usage.
     **/
    public function testSetFailWithoutInit()
    {
        Config::reset(true);
        Config::set('letterpress.testvalue', 'value');

        $this->assertEquals('value', Config::get('letterpress.testvalue'));
    }

    public function testHas() {
        Config::init();

        $this->assertTrue(Config::has('letterpress.locale'));
        $this->assertFalse(Config::has('letterpress.non-existent-value'));
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Config must be initialized before usage.
     **/
    public function testHasFailWithoutInit()
    {
        Config::reset(true);
        Config::has('letterpress.locale');
    }

    public function testApply()
    {
        Config::init();
        Config::apply(['foo' => 'bar', 'fizz.baz' => 'foobar']);

        $this->assertEquals('bar', Config::get('foo'));
        $this->assertEquals('foobar', Config::get('fizz.baz'));
    }

    /**
     * @expectedException \LogicException
     * @expectedExceptionMessage Identifier must be string.
     **/
    public function testApplyFailWithNonStringKey()
    {
        Config::init();

        Config::apply(['testvalue']);
    }
}
