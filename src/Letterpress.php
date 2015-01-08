<?php namespace EFrane\Letterpress;

use EFrane\Letterpress\Integrations\ParsedownFactory;
use EFrane\Letterpress\Integrations\TypoFixerFacade;
use EFrane\Letterpress\Markup\MarkupProcessor;

/**
 *  @author Stefan Graupner <stefan.graupner@gmail.com>
 **/
class Letterpress
{
  protected $parsedown = null;
  protected $fixer = null;
  protected $markup = null;

  public function __construct($config = [])
  {
    // check for initialized config
    try
    {
      Config::get('letterpress.locale');
    } catch (\RuntimeException $e)
    {
      throw new LetterpressException($e->getMessage());
    }

    $this->setup($config);
  }

  protected function setup($config = [])
  {
    // apply additional config
    if (count($config) > 0)
      Config::apply($config);
  }

  public function press($input, $config = [])
  {
    $this->setup($config);

    $output = "";
    
    $output = $this->markdown($input);
    $output = $this->markup($output);
    $output = $this->typofix($output);

    if (strlen($output) === 0)
      $output = $input;

    return $output;
  }

  public function markdown($input, $force = false, $config = [])
  {
    $this->setup($config);

    $this->parsedown = null;
    if (Config::get('letterpress.markdown.enabled') || $force)
      $this->parsedown = ParsedownFactory::create();

    // extract bbcodes
    $bbcodes = [];

    preg_match_all('/(?P<bbcode>\[.+?\].+\[\/.+?\])/', $input, $matches);
    foreach ($matches['bbcode'] as $code)
    {
      $escaped = sha1($code);
      $input = str_replace($code, $escaped, $input);

      $bbcodes[$escaped] = $code;
    }

    $output = ($force || !is_null($this->parsedown))
      ? $this->parsedown->parse($input) 
      : $input;

    // unescape bbcodes
    $output = str_replace(array_keys($bbcodes), array_values($bbcodes), $output);

    return $output;
  }

  public function markup($input, $force = false, $config = [])
  {
    $this->setup($config);

    $this->markup = null;
    if (Config::get('letterpress.markup.enabled') || $force)
      $this->markup = new MarkupProcessor;

    return ($force || !is_null($this->markup))
             ? $this->markup->process($input) 
             : $input;
  }

  public function typofix($input, $force = false, $config = [])
  {
    $this->setup($config);

    $this->fixer = null;
    if (Config::get('letterpress.microtypography.enabled') || $force)
      $this->fixer = new TypoFixerFacade;

    return ($force || !is_null($this->fixer)) 
             ? $this->fixer->fix($input) 
             : $input;
  }
}
