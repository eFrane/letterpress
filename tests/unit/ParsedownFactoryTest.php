<?php

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Integrations\ParsedownFactory;

class ParsedownFactoryTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        Config::init('../config');        
    }

    protected function _after()
    {
    }

    protected function testCreateParsedown()
    {
        // make sure we ask for the standard parsedown
        Config::set('letterpress.enableMarkdownExtra', false);

        $this->assertInstanceof('\Parsedown', ParsedownFactory::create());
    }

    /**
     * @expectedException EFrane\Letterpress\LetterpressException
     * @expectedExceptionMessage Enabling MarkdownExtra requires ParsedownExtra to be installed.
     **/
    protected function testCreateParsedownExtraException()
    {
        Config::set('letterpress.enableMarkdownExtra', true);
        ParsedownFactory::create();
    }
}
