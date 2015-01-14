<?php

use EFrane\Letterpress\Letterpress;
use EFrane\Letterpress\Config;

class YouTubeEmbedTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $lp;

    protected function _before() 
    {
        Config::init('config');
        $this->lp = new Letterpress;
    }

    protected function _after() {}

    public function testBasicEmbed()
    {
        $input = "[youtube]https://www.youtube.com/watch?v=UF8uR6Z6KLc[/youtube]";
        $expected = "<iframe width=\"459\" height=\"344\" "
                  . "src=\"http://www.youtube.com/embed/UF8uR6Z6KLc?feature=oembed\" "
                  . "frameborder=\"0\" allowfullscreen></iframe>";

        $output = $this->lp->press($input, ['letterpress.media.enableResponsiveIFrames' => false]);

        $this->assertEquals($output, $expected);
    }

    public function testResponsiveEmbed()
    {
        $input = "[youtube]https://www.youtube.com/watch?v=UF8uR6Z6KLc[/youtube]";
        $expected = "<div class=\"iframe img-responsive\">"
                  . "<img class=\"ratio\" src=\"//placehold.it/16x9&amp;text=+\" width=\"16\" height=\"9\">"
                  . "<iframe src=\"http://www.youtube.com/embed/UF8uR6Z6KLc?feature=oembed\""
                  . " frameborder=\"0\" allowfullscreen data-width=\"459\" data-height=\"344\">"
                  . "</iframe></div>";
        
        $output = $this->lp->press($input);
        $this->assertEquals($expected, $output);
    }

    public function testEmbedInParagraph()
    {
        $input = "This is a dummy paragraph text. And you should totally look: [youtube]https://www.youtube.com/watch?v=UF8uR6Z6KLc[/youtube] So cool, right?";
        $expected = "";

        $output = $this->lp->press($input);
        $this->assertEquals($output, $expected);
    }
}
