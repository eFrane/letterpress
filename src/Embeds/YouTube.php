<?php namespace EFrane\Letterpress\Embeds;

use EFrane\Letterpress\Config;

/**
 * Handle YouTube embeds.
 *
 * The url matcher regular expression was taken from 
 * http://stackoverflow.com/a/19377429/718752.
 *
 * @author Stefan Graupner <stefan.graupner@gmail.com>
 **/
class YouTube extends VideoEmbed
{
  protected $bbcode = true;

  protected $urlRegex = '/^(http\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+$/';
}
