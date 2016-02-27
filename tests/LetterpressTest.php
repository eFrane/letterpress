<?php

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Letterpress;
use EFrane\Letterpress\Processing\Markdown;
use Masterminds\HTML5\Serializer\HTML5Entities;

class LetterpressTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        parent::tearDown();

        Config::reset(true);
    }

    public function testCreate()
    {
        $lp = new Letterpress();
        $this->assertInstanceOf(Letterpress::class, $lp);
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

    public function testMarkup()
    {
        Config::init();

        $letterpress = new Letterpress(['letterpress.markup.maxHeadlineLevel' => 3]);

        $html = "<h1>This will be an h3 tag</h1>";
        $expected = "<h3>This will be an h3 tag</h3>";

        $this->assertEquals($expected, $letterpress->markup($html));
    }

    public function testPressCallsIntoProcessorFunctions()
    {
        Config::init();

        /* @var $lpMock Letterpress|PHPUnit_Framework_MockObject_MockObject */
        $lpMock = $this->getMock(Letterpress::class, [
            'markdown',
            'typofix',
            'markup'
        ]);

        $lpMock->expects($this->once())->method('markdown');
        $lpMock->expects($this->once())->method('typofix');
        $lpMock->expects($this->once())->method('markup');

        $lpMock->press('Hello World');
    }

    public function testPress()
    {
        $actual =<<<Markdown
# Hello World

I am a simpleminded paragraph with no big aims for my life.
Fortunately though, I was blessed with some *emphasis*.

> This is how it always was and always should
> be: Keep making excuses to quote things that don't exist
- Mariella
Markdown;

        $expected =<<<HTML
<h1>Hello World</h1>
<p>I am a simple&shy;minded para&shy;graph with no big aims for my life.<br>
Fortunately though, I was blessed with some <em>emphasis</em>.</p>
<figure><blockquote>
<p>This is how it always was and always should
be: Keep making excuses to quote things that don&rsquo;t exist</p>

</blockquote><figcaption>Mari&shy;ella</figcaption></figure>
HTML;

        Config::init();

        $lp = new Letterpress();

        $this->assertEquals($expected, $lp->press($actual));
    }
}
