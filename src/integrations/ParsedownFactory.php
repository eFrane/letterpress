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

    return new $class;
  }
}
