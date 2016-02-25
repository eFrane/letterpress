<?php

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Processing\Markdown;

class MarkdownProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();

        Config::init();
    }

    public function tearDown()
    {
        parent::tearDown();

        Config::reset(true);
    }

    public function testMarkdownDefault()
    {
        $actual = 'It\'s a paragraph!';
        $expected = '<p>It\'s a paragraph!</p>';

        $this->assertEquals($expected, Markdown::run($actual));
    }

    public function testMarkdownDisabled()
    {
        Config::set('letterpress.markdown.enabled', false);

        $actual = '# This will not be processed';
        $expected = $actual;

        $this->assertEquals($expected, Markdown::run($actual));
    }

    public function testMarkdownDisabledButForced()
    {
        Config::set('letterpress.markdown.enabled', false);

        $actual = '# This will be processed';
        $expected = '<h1>This will be processed</h1>';

        $this->assertEquals($expected, Markdown::run($actual, true));
    }

    /**
     * @dataProvider bbcodeDeployer
     */
    public function testBBCodeIsCorrectlyEscaped($actual, $expected)
    {
        $this->assertEquals($expected, Markdown::run($actual));
    }

    public function bbcodeDeployer()
    {
        return [
            ['[tag]Content[/tag]', '<p>[tag]Content[/tag]</p>'],
            ['[tag with="attribute"]Content[/tag]', '<p>[tag with="attribute"]Content[/tag]</p>'],
            ['`processed` [tag]*ignored*[/tag]', '<p><code>processed</code> [tag]*ignored*[/tag]</p>'],
        ];
    }
}
