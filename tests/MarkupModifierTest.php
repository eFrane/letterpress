<?php

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Markup\MarkupProcessor;

abstract class MarkupModifierTest extends TestCase
{
    /**
     * @var MarkupProcessor
     */
    protected $processor = null;

    public function setUp(): void
    {
        parent::setUp();

        Config::init();
        Config::set('letterpress.media.enabled', false);

        $this->processor = new MarkupProcessor();
        $this->setUnitUnderTest();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        Config::reset(true);
        $this->processor = null;
    }

    abstract protected function setUnitUnderTest();
}
