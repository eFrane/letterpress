<?php

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Letterpress;

class LetterpressTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException EFrane\Letterpress\LetterpressException
     * @expectedExceptionMessage Config must be initialized before usage.
     */
    public function testCreateFailsWithoutConfig()
    {
        new Letterpress();
    }

    public function testCreateWithInitializedConfig()
    {
        Config::init();

        $this->assertInstanceOf(Config::class, Config::instance());
        $this->assertInstanceOf(Letterpress::class, new Letterpress());

        Config::reset(true);
    }

    public function testCreateWithConfigOverride()
    {
        Config::init();

        new Letterpress([
            'configoption' => 'optionvalue'
        ]);

        $this->assertEquals('optionvalue', Config::get('configoption'));
    }
}
