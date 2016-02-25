<?php

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Integrations\TypoFixerFacade;
use JoliTypo\Fixer;

class TypoFixerFacadeTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        parent::tearDown();

        Config::reset(true);
    }

    public function setUp()
    {
        parent::setUp();

        Config::init();
    }

    /**
     * @expectedException EFrane\Letterpress\LetterpressException
     * @expectedExceptionMessage Typography fixing requires a locale.
     */
    public function testInstantiateFailsWithoutLocale()
    {
        Config::set('letterpress.locale', '');

        new TypoFixerFacade();
    }

    /**
     * @expectedException EFrane\Letterpress\LetterpressException
     * @expectedExceptionMessage Typography fixing requires setting up fixers.
     */
    public function testInstantiateFailsWithoutFixers()
    {
        Config::set('jolitypo.defaults', []);
        Config::set('jolitypo.en_GB', []);
        Config::set('letterpress.microtypography.enableHyphenation', false);

        new TypoFixerFacade();
    }

    /**
     * @dataProvider localeProvider
     */
    public function testValidateLocale($locale, $validity)
    {
        $fixer = new TypoFixerFacade();

        $this->assertEquals($validity, $fixer->validateLocale($locale));
    }

    public function localeProvider()
    {
        return [
            ['de_DE', true],
            ['english', false],
            ['en_US', true],
            ['por_BR', true],
        ];
    }

    public function testGetFacadeProperty()
    {
        $fixer = new TypoFixerFacade();
        $this->assertInstanceOf(Fixer::class, $fixer->facade_fixer);
    }

    public function testSetFacadeProperty()
    {
        $fixer = new TypoFixerFacade();

        $fixer->facade_fixers = [];
        $this->assertEquals([], $fixer->facade_fixers);
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessageRegExp /.+ can not be accessed/
     */
    public function testGetFixerProperty()
    {
        $fixer = new TypoFixerFacade();
        $fixer->property;
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessageRegExp /.+ can not be modified/
     */
    public function testSetFixerProperty()
    {
        $fixer = new TypoFixerFacade();
        $fixer->property = 'value';
    }

    /**
     * @dataProvider fixProvider
     */
    public function testFix($actual, $expected)
    {
        $fixer = new TypoFixerFacade();

        $this->assertEquals($expected, $fixer->fix($actual));
    }

    public function fixProvider()
    {
        return [
            [
                'This will not change.',
                'This will not change.',
            ],

            [
                'Environmental crisis.',
                'Envir&shy;on&shy;mental crisis.',
            ],
        ];
    }
}
