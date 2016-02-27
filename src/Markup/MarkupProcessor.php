<?php

namespace EFrane\Letterpress\Markup;

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Embed\EmbedFactory;
use EFrane\Letterpress\Embed\EmbedRepository;
use EFrane\Letterpress\LetterpressException;
use Masterminds\HTML5;

/**
 * Post-process the generated and typo-fixed markup for additional
 * beautifications.
 *
 * @author Stefan Graupner <stefan.graupner@gmail.com>
 **/
class MarkupProcessor
{
    protected $embedRepository = null;

    /**
     * @var EmbedFactory
     */
    protected $embedFactory = null;

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
        $this->prepareModifiers();
        $this->prepareEmbedRepository();
    }

    public function prepareModifiers()
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

        $this->modifiers[] = new RemoveEmptyNodesModifier();
    }

    protected function prepareEmbedRepository()
    {
        if (Config::get('letterpress.media.enabled')) {
            // transform the enabled media services into their embed class names
            $enabledExternalServices = Config::get('letterpress.media.services');
            $embedClasses = [];

            foreach ($enabledExternalServices as $service) {
                $className = (!strstr($service, '\\'))
                    ? sprintf('EFrane\Letterpress\Embeds\%s', $service)
                    : $service;

                if (class_exists($className) && is_a($className, 'EFrane\Letterpress\Embeds\EmbedWorker')) {
                    $embedClasses[] = $className;
                }
            }

            $this->embedRepository = new EmbedRepository();
            $this->embedFactory = new EmbedFactory($embedClasses);
        }
    }

    public function resetModifiers()
    {
        $this->setModifiers();
    }

    public function setModifiers(array $modifiers = [])
    {
        $this->modifiers = $modifiers;
    }

    /**
     * @return EFrane\Letterpress\Embeds\EmbedRepository
     */
    public function getEmbedRepository()
    {
        return $this->embedRepository;
    }

    public function process($content)
    {
        if (strlen($content) == 0) return '';

        $fragment = $this->html5->loadHTMLFragment($content);

        $fragment = $this->processEmbeds($fragment);
        $fragment = $this->processModifiers($fragment);

        return $this->html5->saveHTML($fragment);
    }

    /**
     * @param $fragment
     * @return mixed
     **/
    protected function processModifiers($fragment)
    {
        foreach ($this->modifiers as $modifier) {
            $modifier->modify($fragment);
        }

        return $fragment;
    }

    /**
     * @param $fragment
     * @return mixed
     **/
    protected function processEmbeds($fragment)
    {
        if (!is_null($this->embedFactory)) {
            $fragment = $this->embedFactory->run($fragment, $this->embedRepository);

            return $fragment;
        }

        return $fragment;
    }
}
