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
        $expected = "<iframe width=\"459\" height=\"344\" src=\"http://www.youtube.com/embed/UF8uR6Z6KLc?feature=oembed\" frameborder=\"0\" allowfullscreen></iframe>";

        $output = $this->lp->press($input);

        $this->assertEquals($output, $expected);
    }
}
