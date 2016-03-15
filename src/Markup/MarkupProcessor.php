<?php

namespace EFrane\Letterpress\Markup;

use EFrane\Letterpress\Config;
use EFrane\Letterpress\LetterpressException;
use EFrane\Letterpress\Markup\RichMedia\MediaModifier;
use EFrane\Letterpress\Markup\RichMedia\Repository;
use hanneskod\classtools\Iterator\ClassIterator;
use Masterminds\HTML5;
use Symfony\Component\Finder\Finder;

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

    /**
     * @var Repository
     */
    protected $mediaRepository = null;

    public function __construct(Repository $mediaRepository = null)
    {
        $this->html5 = new HTML5();

        $this->setMediaRepository($mediaRepository);
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
            $this->loadMediaModifiers();
        }

        $this->modifiers[] = new RemoveEmptyNodesModifier();
    }

    protected function loadMediaModifiers()
    {
        if (!is_a($this->mediaRepository, Repository::class)) {
            throw new LetterpressException('Media repository was not initialized.');
        }

        $iter = new ClassIterator((new Finder())->in(__DIR__ . DIRECTORY_SEPARATOR . 'RichMedia'));

        $iter->enableAutoloading();

        $mediaModifiers = collect();
        foreach ($iter as $mediaModifier) {
            /** @var $mediaModifier \ReflectionClass */
            if ($mediaModifier->isSubclassOf(MediaModifier::class)) {
                $mediaModifiers[$mediaModifier->getShortName()] = $mediaModifier->getName();
            }
        }

        foreach (Config::get('letterpress.media.services') as $serviceName) {
            if ($mediaModifiers->has($serviceName)) {
                $class = $mediaModifiers->get($serviceName);
                $this->modifiers[] = new $class($this->mediaRepository);
            } else {
                throw new LetterpressException('Rich media integration for ' . $serviceName . ' is not available.');
            }
        }
    }

    /**
     * @return Repository
     */
    public function getMediaRepository()
    {
        return $this->mediaRepository;
    }

    /**
     * @param Repository $mediaRepository
     */
    public function setMediaRepository($mediaRepository)
    {
        $this->mediaRepository = $mediaRepository;
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
