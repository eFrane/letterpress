<?php

use EFrane\Letterpress\Config;
#use EFrane\Letterpress\LetterpressException;

use EFrane\Letterpress\Integrations\TypoFixerFacade;

class TypoFixerFacadeTest extends \Codeception\TestCase\Test
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

    
    public function testCreate()
    {
        Config::init();
        $fixer = new TypoFixerFacade;

        $this->assertInstanceOf('EFrane\Letterpress\Integrations\TypoFixerFacade', $fixer);
    }

    /**
     * @expectedException EFrane\Letterpress\LetterpressException
     * @expectedExceptionMessage Typography fixing requires a locale.
     **/
    public function testCreateWithEmptyLocale()
    {
        Config::set('letterpress.locale', '');

        $fixer = new TypoFixerFacade;
    }

    /**
     * @expectedException EFrane\Letterpress\LetterpressException
     * @expectedExceptionMessage Typography fixing requires a locale.
     **/
    public function testCreateWithInvalidLocale()
    {
        Config::set('letterpress.locale', 42);

        $fixer = new TypoFixerFacade;
    }

    /**
     * @expectedException EFrane\Letterpress\LetterpressException
     * @expectedExceptionMessage Typography fixing requires setting up fixers.
     **/
    public function testCreateWithEmptyFixers()
    {
        Config::set('letterpress.locale', 'en_GB'); // reset locale
        Config::set('letterpress.microtypography.useDefaults', false); // disable default fixers
        Config::set('letterpress.microtypography.additionalFixers', []); // reset additional fixers
        Config::set('letterpress.microtypography.enableHyphenation', false); // disable hyphenation

        $fixer = new TypoFixerFacade;
    }

    public function testGet()
    {
        Config::reset();
        $fixer = new TypoFixerFacade;

        $this->assertInstanceOf('JoliTypo\Fixer', $fixer->facade_fixer);
    }

    public function testFix()
    {
        $input = "<p>Letterpress is great, but...you know, standing on the shoulders of giants.</p>";
        $expected = "<p>Letter&shy;press is great, but&hellip;you know, stand&shy;ing on the shoulders of giants.</p>";

        $fixer = new TypoFixerFacade;
        $this->assertEquals($expected, $fixer->fix($input));
    }
}
