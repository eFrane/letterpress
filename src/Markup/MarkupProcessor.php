<?php

namespace EFrane\Letterpress\Markup;

use EFrane\Letterpress\Config;
use Masterminds\HTML5;

/**
 * Post-process the generated and typo-fixed markup for additional
 * beautifications.
 *
 * @author Stefan Graupner <stefan.graupner@gmail.com>
 **/
class MarkupProcessor
{
    /**
     * @var \Masterminds\HTML5
     */
    protected $html5 = null;

    /**
     * @var BaseModifier[]
     */
    protected $modifiers = [];

    public function __construct()
    {
        $this->html5 = new HTML5();
        $this->loadModifiersFromConfig();
    }

    public function loadModifiersFromConfig()
    {
        $this->modifiers = [];

        if (Config::get('letterpress.markup.blockQuoteFix')) {
            $this->modifiers[] = new BlockQuoteModifier();
        }

        $maxHeadlineLevel = Config::get('letterpress.markup.maxHeadlineLevel');
        if ($maxHeadlineLevel > 1) {
            $this->modifiers[] = new HeadlineLevelModifier($maxHeadlineLevel);
        }

        if (Config::get('letterpress.markup.addLanguageInfo')) {
            $this->modifiers[] = new LanguageCodeModifier();
        }

        if (Config::get('letterpress.media.enabled')) {
            foreach (Config::get('letterpress.media.services') as $serviceName) {
                // find class
                // throw exception if not found
                // instantiate modifier
            }
        }

        $this->modifiers[] = new RemoveEmptyNodesModifier();
    }

    public function resetModifiers()
    {
        $this->setModifiers();
    }

    public function getModifiers()
    {
        return $this->modifiers;
    }

    public function setModifiers(array $modifiers = [])
    {
        $this->modifiers = $modifiers;
    }

    public function process($content)
    {
        if (strlen($content) == 0) {
            return '';
        }

        $fragment = $this->html5->loadHTMLFragment($content);

        $fragment = $this->processModifiers($fragment);

        return $this->html5->saveHTML($fragment);
    }

    /**
     * @param $fragment
     *
     * @return mixed
     **/
    protected function processModifiers($fragment)
    {
        foreach ($this->modifiers as $modifier) {
            $modifier->modify($fragment);
        }

        return $fragment;
    }
}
