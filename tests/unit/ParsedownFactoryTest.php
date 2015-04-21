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
      Config::init();
    }

    protected function _after()
    {

    }

    public function testCreateParsedown()
    {
        // make sure we ask for the standard parsedown
        Config::set('letterpress.markdown.useMarkdownExtra', false);
        $this->assertInstanceof('\Parsedown', ParsedownFactory::create());
    }

    public function testParsedownParse()
    {
        $factory = ParsedownFactory::create();
        $result = $factory->parse('# headline');
        $this->assertEquals('<h1>headline</h1>', $result);
    }

    /**
     * @expectedException EFrane\Letterpress\LetterpressException
     * @expectedExceptionMessage Enabling MarkdownExtra requires ParsedownExtra to be installed.
     **/
    public function testCreateParsedownExtraException()
    {
        Config::set('letterpress.markdown.useMarkdownExtra', true);
        ParsedownFactory::create();
    }
}
