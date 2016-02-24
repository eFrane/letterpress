<?php

use EFrane\Letterpress\Config;

class ConfigTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        parent::tearDown();

        Config::reset(true);
    }

    public function setUp()
    {
        parent::setUp();

        Config::init();
    }

    public function testDefaultConfigPathExists()
    {
        $configPath = Config::getDefaultConfigPath();

        $this->assertFileExists($configPath);
    }

    public function testInitialize()
    {
        Config::reset(true);
        Config::init();

        $this->assertInstanceOf(Config::class, Config::instance());
    }

    public function testInitializeWithValueOverride()
    {
        Config::reset(true);
        $this->assertNotInstanceOf(Config::class, Config::instance());

        Config::init([
            'test.value' => 'foo',
        ]);

        $this->assertInstanceOf(Config::class, Config::instance());
        $this->assertEquals('foo', Config::get('test.value'));
    }

    public function testGetExistingValue()
    {
        //$expected = ['letterpress.markdown.enabled' => 'true'];
        $this->assertEquals(true, Config::get('letterpress.markdown.enabled'));
    }

    /**
     * @expectedException EFrane\Letterpress\LetterpressException
     * @expectedExceptionMessage Config must be initialized before usage.
     */
    public function testGetNotInitialized()
    {
        Config::reset(true);

        Config::get('letterpress.locale');
    }

    public function testIsSingleton()
    {
        $reflection = new ReflectionClass(Config::class);

        $constructor = $reflection->getConstructor();
        $this->assertFalse($constructor->isPublic());

        $clone = $reflection->getMethod('__clone');
        $this->assertTrue($clone->isPrivate());
    }

    /**
     * @expectedException LogicException
     * @expectedExceptionMessage Identifier must be string.
     */
    public function testApplyNonStringKey()
    {
        Config::apply([42 => 'value']);
    }
}
