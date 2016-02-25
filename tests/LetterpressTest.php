<?php

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Letterpress;

class LetterpressTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        parent::tearDown();

        Config::reset(true);
    }

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
    }

    public function testCreateWithConfigOverride()
    {
        Config::init();
        $this->assertEquals('en_GB', Config::get('letterpress.locale'));

        new Letterpress([
            'configoption'       => 'optionvalue',
            'letterpress.locale' => 'de_DE',
        ]);

        $this->assertEquals('optionvalue', Config::get('configoption'));
        $this->assertEquals('de_DE', Config::get('letterpress.locale'));
    }

    public function testMarkdown()
    {
        Config::init();

        $letterpress = new Letterpress();

        $markdown = '# Hello World';
        $html = '<h1>Hello World</h1>';

        $this->assertEquals($html, $letterpress->markdown($markdown));
    }

    public function testMicrotypography()
    {
        Config::init();

        $letterpress = new Letterpress();

        $html = 'This is an example text with a very long word at the end: supercalifragilisticexpialigocious.';
        $expected = 'This is an example text with a very long word at the end: super&shy;cal&shy;i&shy;fra&shy;gil&shy;istic&shy;ex&shy;pi&shy;ali&shy;go&shy;cious.';

        $this->assertEquals($expected, $letterpress->typofix($html));
    }

//    public function testMarkup()
//    {
//        Config::init();
//
//        $letterpress = new Letterpress(['markup.maxHeadlineLevel' => 3]);
//
//        $html = "<h1>This will be an h3 tag</h1>";
//        $expected = "<h3>This will be an h3 tag</h3>";
//
//        $this->assertEquals($expected, $letterpress->markup($html));
//    }
}
