<?php namespace EFrane\Letterpress;

/**
 *  @author Stefan Graupner <stefan.graupner@gmail.com>
 **/
class Letterpress
{
  protected $parsedown = null;
  protected $fixer = null;
  protected $markup = null;

  public function __construct()
  {
    // check for initialized config
    try
    {
      Config::get('letterpress.locale');
    } catch (\RuntimeException $e)
    {
      throw new LetterpressException($e->getMessage());
    }

    $this->setup();
  }

  protected function setup($config = [])
  {
    // apply additional config
    if (count($config) > 0)
      Config::apply($config);

    // TODO: only reset these if dependent configuration options changed
    $this->parsedown = null;
    if (Config::get('letterpress.markdown.enabled'))
      $this->parsedown = Integrations\ParsedownFactory::create();

    $this->fixer = null;
    if (Config::get('letterpress.microtypography.enabled'))
      $this->fixer = new Integrations\TypoFixerFacade;

    $this->markup = null;
    if (Config::get('letterpress.markup.enabled'))
      $this->markup = new Markup\MarkupProcessor;
  }

  public function press($input, $config = [])
  {
    $this->setup($config);

    $output = "";

    if (!is_null($this->parsedown))
      $output = $this->parsedown->parse($input);

    if (!is_null($this->markup))
      $output = $this->markup->process($output);

    if (!is_null($this->fixer))
      $output = $this->fixer->fix($output);

    if (strlen($output) === 0)
      $output = $input;

    return $output;
  }
}
