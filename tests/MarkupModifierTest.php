<?php

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Markup\MarkupProcessor;

abstract class MarkupModifierTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var MarkupProcessor
     */
    protected $processor = null;

    public function setUp()
    {
        parent::setUp();

        Config::init();
        Config::set('letterpress.media.enabled', false);

        $this->processor = new MarkupProcessor();
        $this->setUnitUnderTest();
    }

    public function tearDown()
    {
        parent::tearDown();

        Config::reset(true);
        $this->processor = null;
    }

    abstract protected function setUnitUnderTest();
}
