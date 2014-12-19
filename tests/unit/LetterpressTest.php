<?php

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Letterpress;

class LetterpressTest extends \Codeception\TestCase\Test
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

    /**
     * @expectedException EFrane\Letterpress\LetterpressException
     **/
    public function testCreateWithoutConfig()
    {
        Config::reset(true);
        $letterpress = new Letterpress();
    }

    public function testCreate()
    {
        Config::init('config');
        $letterpress = new Letterpress();
    }
}
