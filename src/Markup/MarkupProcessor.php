<?php namespace EFrane\Letterpress\Markup;

use \HTML5;

use EFrane\Letterpress\Config;
use EFrane\Letterpress\LetterpressException;

use EFrane\Letterpress\Embeds\EmbedRepository;

/**
 * Post-process the generated and typo-fixed markup for additional
 * beautifications.
 * 
 * @author Stefan Graupner <stefan.graupner@gmail.com>
 **/
class MarkupProcessor
{
  protected $embedRepository = null;
  protected $modifiers = [];

  public function __construct()
  {
    $this->prepareModifiers();
    $this->prepareEmbedRepository();
  }

  protected function prepareEmbedRepository()
  {
    if (Config::get('letterpress.media.enabled'))
    {
      // transform the enabled media services into their embed class names
      $enabledExternalServices = Config::get('letterpress.media.services');
      $embedClasses = [];

      foreach ($enabledExternalServices as $service)
      {
        $className = (!strstr($service, '\\'))
          ? sprintf('EFrane\Letterpress\Embeds\%s', $service)
          : $service;

        if (class_exists($className))
          $embedClasses[] = $className;
      }

      $this->embedRepository = new EmbedRepository($embedClasses);
    }

  }

  protected function prepareModifiers()
  {
    $this->modifiers = [];

    if (Config::get('letterpress.markup.blockQuoteFix'))
      $this->modifiers[] = new BlockQuoteModifier;

    $maxHeadlineLevel = Config::get('letterpress.markup.maxHeadlineLevel');
    if ($maxHeadlineLevel > 1)
      $this->modifiers[] = new HeadlineLevelModifier($maxHeadlineLevel);

    if (Config::get('letterpress.markup.addLanguageInfo'))
      $this->modifiers[] = new LanguageCodeModifier;

    $this->modifiers[] = new RemoveEmptyNodesModifier;
  }

  public function process($content)
  {
    $fragment = HTML5::loadHTMLFragment($content);

    if (!is_null($this->embedRepository))
      $fragment = $this->embedRepository->apply($fragment);

    foreach ($this->modifiers as $modifier)
    {
      $modifiedFragment = $modifier->modify($fragment);
      if (!is_null($modifiedFragment))
      {
        $fragment = $modifiedFragment;
      } else
      {
        throw new LetterpressException("Failed to apply modifier ".get_class($modifier));
      }
    }

    return HTML5::saveHTML($fragment);
  }
}
