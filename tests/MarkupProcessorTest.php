<?php

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Markup\BlockQuoteModifier;
use EFrane\Letterpress\Markup\HeadlineLevelModifier;
use EFrane\Letterpress\Markup\LanguageCodeModifier;
use EFrane\Letterpress\Markup\MarkupProcessor;
use EFrane\Letterpress\Markup\RemoveEmptyNodesModifier;

class MarkupProcessorTest extends PHPUnit_Framework_TestCase
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

    public function testLoadsDefaultModifiers()
    {
        $processor = new MarkupProcessor();

        $this->assertInstanceOf(MarkupProcessor::class, $processor);

        $modifiers = $this->getModifierClassNames($processor);

        $this->assertContains(BlockQuoteModifier::class, $modifiers);
        $this->assertContains(RemoveEmptyNodesModifier::class, $modifiers);
    }

    /**
     * @param $processor
     * @return static
     **/
    protected function getModifierClassNames(MarkupProcessor $processor)
    {
        $modifiers = collect($processor->getModifiers())->map(function ($mod) {
            return get_class($mod);
        });

        return $modifiers;
    }

    public function testLoadsHeadlineLevelModifier()
    {
        Config::set('letterpress.markup.maxHeadlineLevel', 2);

        $processor = new MarkupProcessor();

        $modifiers = $this->getModifierClassNames($processor);

        $this->assertContains(BlockQuoteModifier::class, $modifiers);
        $this->assertContains(RemoveEmptyNodesModifier::class, $modifiers);
        $this->assertContains(HeadlineLevelModifier::class, $modifiers);
    }

    public function testUnloadsBlockQuoteModifier()
    {
        Config::set('letterpress.markup.blockQuoteFix', false);

        $processor = new MarkupProcessor();

        $modifiers = $this->getModifierClassNames($processor);

        $this->assertNotContains(BlockQuoteModifier::class, $modifiers);
        $this->assertContains(RemoveEmptyNodesModifier::class, $modifiers);
    }

    public function testLoadsLanguageCodeModifier()
    {
        Config::set('letterpress.markup.addLanguageInfo', true);

        $processor = new MarkupProcessor();

        $modifiers = $this->getModifierClassNames($processor);

        $this->assertContains(BlockQuoteModifier::class, $modifiers);
        $this->assertContains(RemoveEmptyNodesModifier::class, $modifiers);
        $this->assertContains(LanguageCodeModifier::class, $modifiers);
    }
}
