<?php

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Integrations\TypoFixerFacade;
use JoliTypo\Fixer;

class TypoFixerFacadeTest extends TestCase
{
    public function tearDown(): void
    {
        parent::tearDown();

        Config::reset(true);
    }

    public function setUp(): void
    {
        parent::setUp();

        Config::init();
    }

    public function testInstantiateFailsWithoutLocale()
    {
        $this->expectException(\EFrane\Letterpress\LetterpressException::class);
        $this->expectExceptionMessage('Invalid locale.');

        Config::set('letterpress.locale', '');

        new TypoFixerFacade();
    }

    public function testInstantiateFailsWithoutFixers()
    {
        $this->expectException(\EFrane\Letterpress\LetterpressException::class);
        $this->expectExceptionMessage('Typography fixing requires setting up fixers.');

        Config::set('jolitypo.defaults', []);
        Config::set('jolitypo.en_GB', []);
        Config::set('letterpress.microtypography.enableHyphenation', false);

        new TypoFixerFacade();
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

    public function testGetFixerProperty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/.+ can not be accessed/');

        $fixer = new TypoFixerFacade();
        $fixer->property;
    }

    public function testSetFixerProperty()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/.+ can not be modified/');

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
