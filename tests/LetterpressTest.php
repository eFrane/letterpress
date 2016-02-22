<?php

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
}
