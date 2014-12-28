<?php namespace EFrane\Letterpress\Markup;

use \HTML5;

use EFrane\Letterpress\Config;
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
    $this->prepareEmbedRepository();
    $this->prepareModifiers();
  }

  protected function prepareEmbedRepository()
  {
    // transform the enabled media services into their embed class names
    $enabledExternalServices = Config::get('letterpress.media');
    $embedClasses = [];

    foreach ($enabledExternalServices as $service => $enabled)
    {
      if (is_bool($enabled) && $enabled)
      {
        $name = ucfirst(strtolower($service));  
        $embedClasses[] = sprintf('EFrane\Letterpress\Embeds\%sEmbed', $name);
      }

      if (is_string($enabled) && class_exists($enabled))
        $embedClasses[] = $enabled;
    }

    $this->embedRepository = new EmbedRepository($embedClasses);
  }

  protected function prepareModifiers()
  {
    $this->modifiers = [];

    if (Config::get('letterpress.markup.blockQuoteFix'))
      $this->modifiers['blockQuote'] = new BlockQuoteModifier;

    $maxHeadlineLevel = Config::get('letterpress.markup.maxHeadlineLevel');
    if ($maxHeadlineLevel > 1)
      $this->modifiers['headlineLevel'] = new HeadlineLevelModifier($maxHeadlineLevel);

    if (Config::get('letterpress.markup.addLanguageInfo'))
      $this->modifiers['languageCode'] = new LanguageCodeModifier;
  }

  public function process($content)
  {
    $fragment = HTML5::loadHTMLFragment($content);

#    $this->embedRepository->apply($fragment);

    foreach ($this->modifiers as $modifier)
    {
      $modifiedFragment = $modifier->modify($fragment);
      if (!is_null($modifiedFragment))
      {
        $fragment = $modifiedFragment;
      } else
      {
        var_dump($modifier); exit;
      }
    }

    return HTML5::saveHTML($fragment);
  }
}
