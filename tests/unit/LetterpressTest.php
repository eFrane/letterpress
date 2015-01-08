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
        $letterpress = new Letterpress;
    }

    public function testPress()
    {
        $input = "# Hello World\nParagraph Text.";
        $expected = "<h1>Hello World</h1>\n<p>Para&shy;graph Text.</p>";

        $letterpress = new Letterpress;
        $this->assertEquals($expected, $letterpress->press($input));
    }

    public function testPressWithConfigChange()
    {
        $input = "# Hello World\nParagraph Text.";
        $expected = "<h1>Hello World</h1>\n<p>Paragraph Text.</p>";

        $letterpress = new Letterpress;
        $output = $letterpress->press($input, ['letterpress.microtypography.enabled' => false]);

        $this->assertEquals($expected, $output);
    }

    public function testMarkdownOnly()
    {
        $input    = "# Hello World";
        $expected = "<h1>Hello World</h1>";

        $letterpress = new Letterpress;
        $this->assertEquals($expected, $letterpress->markdown($input, true));
    }

    public function testMarkupOnly()
    {
        $input    = "<h1>Hello World</h1>";
        $expected = "<h2>Hello World</h2>";

        Config::set('letterpress.markup.maxHeadlineLevel', 2);
        $letterpress = new Letterpress;

        $this->assertEquals($expected, $letterpress->markup($input, true));
    }

    public function testFixerOnly()
    {
        $input    = "Supercalifragilisticexpialigocious";
        $expected = "Super&shy;cal&shy;i&shy;fra&shy;gil&shy;istic&shy;ex&shy;pi&shy;ali&shy;go&shy;cious";

        $letterpress = new Letterpress(['letterpress.microtypography.enabled' => true]);
        $this->assertEquals($expected, $letterpress->typofix($input, true));
    }
}
