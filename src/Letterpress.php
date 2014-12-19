<?php namespace EFrane\Letterpress;

class Letterpress
{
  protected $parsedown = null;
  protected $fixer = null;

  public function __construct()
  {
    $this->setup();
  }

  protected function setup($config = [])
  {
    // check for initialized config
    try
    {
      Config::get('letterpress.locale');
    } catch (\RuntimeException $e)
    {
      throw new LetterpressException($e->getMessage());
    }

    // apply additional config
    $reset = true;
    if (count($config) > 0)
    {
      Config::apply($config);
    } else
    {
      $reset = false;
    }

    // TODO: only reset these if dependent configuration options changed
    if ($reset)
    {
      if (Config::get('letterpress.markdown.enabled'))
      {
        $this->parsedown = Integrations\ParsedownFactory::create();
      } else
      {
        $this->parsedown = null;
      }

      if (Config::get('letterpress.microtypography.enabled'))
      {
        $this->fixer = new Integrations\TypoFixerFacade;
      } else
      {
        $this->fixer = null;
      }
    }
  }

  public function press($input, $config = [])
  {
    $this->setup($config);

    $output = "";
    if (!is_null($this->parsedown))
      $output = $this->parsedown->parse($input);

    if (!is_null($this->fixer))
      $output = $this->fixer->fix($output);

    return $output;
  }
}
