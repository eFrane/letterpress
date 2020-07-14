<?php

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Markup\BlockQuoteModifier;
use EFrane\Letterpress\Markup\HeadlineLevelModifier;
use EFrane\Letterpress\Markup\LanguageCodeModifier;
use EFrane\Letterpress\Markup\MarkupProcessor;
use EFrane\Letterpress\Markup\RemoveEmptyNodesModifier;
use EFrane\Letterpress\Markup\RichMedia\Repository;

class MarkupProcessorTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Config::init();

        // NOTE: this explicitly does NOT test media modifiers
        Config::set('letterpress.media.enabled', false);
    }

    public function tearDown(): void
    {
        parent::tearDown();

        Config::reset(true);
    }

    public function testLoadsDefaultModifiers()
    {
        $processor = new MarkupProcessor(new Repository());

        $this->assertInstanceOf(MarkupProcessor::class, $processor);

        $modifiers = $this->getModifierClassNames($processor);

        $this->assertContains(BlockQuoteModifier::class, $modifiers);
        $this->assertContains(RemoveEmptyNodesModifier::class, $modifiers);
    }

    /**
     * @param $processor
     *
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

        $modifiers = $this->getModifierClassNames(new MarkupProcessor(new Repository()));

        $this->assertContains(BlockQuoteModifier::class, $modifiers);
        $this->assertContains(HeadlineLevelModifier::class, $modifiers);
        $this->assertContains(RemoveEmptyNodesModifier::class, $modifiers);
    }

    public function testUnloadsBlockQuoteModifier()
    {
        Config::set('letterpress.markup.blockQuoteFix', false);

        $modifiers = $this->getModifierClassNames(new MarkupProcessor(new Repository()));

        $this->assertNotContains(BlockQuoteModifier::class, $modifiers);
        $this->assertContains(RemoveEmptyNodesModifier::class, $modifiers);
    }

    public function testLoadsLanguageCodeModifier()
    {
        Config::set('letterpress.markup.addLanguageInfo', true);

        $modifiers = $this->getModifierClassNames(new MarkupProcessor(new Repository()));

        $this->assertContains(BlockQuoteModifier::class, $modifiers);
        $this->assertContains(RemoveEmptyNodesModifier::class, $modifiers);
        $this->assertContains(LanguageCodeModifier::class, $modifiers);
    }
}
