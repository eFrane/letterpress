<?php namespace EFrane\Letterpress\Integrations;

use EFrane\Letterpress\LetterpressException;
use EFrane\Letterpress\Config;

class ParsedownFactory implements Factory
{
  public static function create()
  {
    $class = '\Parsedown';

    if (Config::get('letterpress.enableMarkdownExtra'))
    {
      if (!class_exists('\ParsedownExtra'))
        throw new LetterpressException("Enabling MarkdownExtra requires ParsedownExtra to be installed.");
      $class = '\ParsedownExtra';
    }

    $instance = new $class;
    
    $instance->setBreaksEnabled(Config::get('letterpress.markdown.enableLineBreaks'));
    $instance->setMarkupEscaped(!Config::get('letterpress.markdown.enableParserInMarkup'));

    return $instance;
  }
}
