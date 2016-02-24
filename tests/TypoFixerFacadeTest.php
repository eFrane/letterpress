<?php

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Integrations\TypoFixerFacade;

class TypoFixerFacadeTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        parent::tearDown();

        Config::reset(true);
    }

    /**
     * @expectedException EFrane\Letterpress\LetterpressException
     * @expectedExceptionMessage Typography fixing requires a locale.
     */
    public function testInstantiateFailsWithoutLocale()
    {
        Config::init();
        Config::set('letterpress.locale', '');

        new TypoFixerFacade();
    }
}
