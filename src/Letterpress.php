<?php namespace EFrane\Letterpress;

class Letterpress
{
  protected $parsedown = null;

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

    $this->parsedown = Integrations\ParsedownFactory::create();
  }

  public function press($html, $config = [])
  {

  }
}
