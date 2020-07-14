<?php

use EFrane\Letterpress\Config;

class ConfigTest extends TestCase
{
    public function tearDown(): void
    {
        parent::tearDown();

        Config::reset(true);
    }

    public function setUp(): void
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

    public function testReset()
    {
        Config::reset(true);

        $this->assertNotInstanceOf(Config::class, Config::instance());
        $this->assertFalse(Config::isInitialized());

        Config::reset();

        $this->assertInstanceOf(Config::class, Config::instance());
        $this->assertTrue(Config::isInitialized());
    }

    public function testGetExistingValue()
    {
        //$expected = ['letterpress.markdown.enabled' => 'true'];
        $this->assertEquals(true, Config::get('letterpress.markdown.enabled'));
    }

    /**
     * @expectedExceptionMessage
     */
    public function testGetNotInitialized()
    {
        $this->expectException(EFrane\Letterpress\LetterpressException::class);
        $this->expectExceptionMessage('Config must be initialized before usage.');

        Config::reset(true);

        Config::get('letterpress.locale');
    }

    public function testIsSingleton()
    {
        $reflection = new ReflectionClass(Config::class);

        $constructor = $reflection->getConstructor();
        $this->assertFalse($constructor->isPublic());
    }

    public function testApplyNonStringKey()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Identifier must be string');

        Config::apply([42 => 'value']);
    }

    public function testClone()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cloning Letterpress\Config is forbidden.');

        Config::init();
        $cfg = clone Config::instance();
    }
}
