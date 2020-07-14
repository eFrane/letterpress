<?php


use EFrane\Letterpress\Config;
use EFrane\Letterpress\Integrations\ParsedownFactory;
use EFrane\Letterpress\LetterpressException;

class ParsedownFactoryTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Config::init();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        Config::reset(true);
    }

    public function testUseMarkdownExtraDefault()
    {
        $this->expectException(LetterpressException::class);
        $this->expectExceptionMessage('Enabling MarkdownExtra requires ParsedownExtra to be installed.');

        Config::set('letterpress.markdown.useMarkdownExtra', true);

        ParsedownFactory::create();
    }

//    public function testUseMarkdownExtraInstalled()
//    {
//        exec('composer require erusev/parsedown-extra');
//        Config::set('letterpress.markdown.useMarkdownExtra', true);
//
//        $this->assertInstanceOf('\ParsedownExtra', ParsedownFactory::create());
//
//        exec('composer remove erusev/parsedown-extra');
//    }

    /**
     * @dataProvider lineBreaksData
     */
    public function testLineBreaks($enabled, $actual, $expected)
    {
        Config::set('letterpress.markdown.enableLineBreaks', $enabled);
        $instance = ParsedownFactory::create();
        $this->assertEquals($expected, $instance->text($actual));
    }

    public function lineBreaksData()
    {
        return [
            [true, 'No breaks.', '<p>No breaks.</p>'],
            [false, 'No breaks.', '<p>No breaks.</p>'],
            [true, "One break  \nNew line", "<p>One break<br />\nNew line</p>"],
            [false, 'Should not break after  ', '<p>Should not break after  </p>'],
        ];
    }
}
