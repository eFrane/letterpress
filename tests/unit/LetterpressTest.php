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

    public function testPress()
    {
        $input = "# Hello World\nParagraph Text.";
        $expected = "<h1>Hello World</h1>\n<p>Para&shy;graph Text.</p>";

        $letterpress = new Letterpress();
        $this->assertEquals($expected, $letterpress->press($input));
    }

    public function testPressWithConfigChange()
    {
        $input = "# Hello World\nParagraph Text.";
        $expected = "<h1>Hello World</h1>\n<p>Paragraph Text.</p>";

        Config::init('config');
        $letterpress = new Letterpress();
        $this->assertEquals($expected, $letterpress->press($input, ['letterpress.microtypography.enabled' => false]));   
    }
}
