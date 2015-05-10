<?php namespace EFrane\Letterpress;

use EFrane\Letterpress\Integrations\ParsedownFactory;
use EFrane\Letterpress\Integrations\TypoFixerFacade;
use EFrane\Letterpress\Markup\MarkupProcessor;

/**
 *  @author Stefan Graupner <stefan.graupner@gmail.com>
 **/
class Letterpress
{
  /**
   * @var EFrane\Letterpress\Embeds\EmbedRepository
   *
   */
  protected $lastEmbedRepository = null;

  /**
   * Setup a new Letterpress instance
   *
   * Please note that there will only be one configuration object used by all
   * letterpress instances. It is however always possible to override specific
   * or all config options on calls to `press()` &c.
   *
   * @param array $config
   */
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


  /**
   * This will return the last embed repository.
   *
   * The embed repository is overridden whenever the markup
   * processor runs with enabled embed processing.
   *
   * @return EFrane\Letterpress\Embeds\EmbedRepository
   **/
  public function getEmbedRepository()
  {
    return $this->lastEmbedRepository;
  }

  /**
   * @param array $config
   **/
  protected function setup($config = [])
  {
    // apply additional config
    if (count($config) > 0)
      Config::apply($config);
  }

  /**
   * @param $input
   * @param array $config
   * @return mixed|string
   **/
  public function press($input, $config = [])
  {
    $this->setup($config);

    $output = "";
    
    $output = $this->markdown($input);
    $output = $this->markup($output);
    $output = $this->typofix($output);

    if (strlen($output) === 0)
      $output = $input;

    //Config::reset();

    return $output;
  }

  /**
   * @param $input
   * @param bool $force
   * @param array $config
   * @return mixed
   **/
  public function markdown($input, $force = false, $config = [])
  {
    $this->setup($config);

    // extract bbcodes
    $bbcodes = [];

    preg_match_all('/(?P<bbcode>\[.+?\].+\[\/.+?\])/', $input, $matches);
    foreach ($matches['bbcode'] as $code)
    {
      $escaped = sha1($code);
      $input = str_replace($code, $escaped, $input);

      $bbcodes[$escaped] = $code;
    }

    $output = $input;
    if (Config::get('letterpress.markdown.enabled') || $force)
    {
      $parsedown = ParsedownFactory::create();
      $output = $parsedown->parse($input);
    }

    // unescape bbcodes
    $output = str_replace(array_keys($bbcodes), array_values($bbcodes), $output);

    return $output;
  }

  /**
   * @param $input
   * @param bool $force
   * @param array $config
   * @return mixed
   **/
  public function markup($input, $force = false, $config = [])
  {
    $this->setup($config);

    $output = $input;
    if (Config::get('letterpress.markup.enabled') || $force)
    {
      $markup = new MarkupProcessor;
      $output = $markup->process($input);

      $this->lastEmbedRepository = $markup->getEmbedRepository();
    }

    return $output;
  }

  /**
   * @param $input
   * @param bool $force
   * @param array $config
   * @return mixed
   **/
  public function typofix($input, $force = false, $config = [])
  {
    $this->setup($config);

    $output = $input;
    if (Config::get('letterpress.microtypography.enabled') || $force)
    {
      $fixer = new TypoFixerFacade;
      $output = $fixer->facade_fixer->fix($input);
    }

    return $output;
  }
}
