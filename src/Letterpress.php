<?php namespace EFrane\Letterpress;

class Letterpress
{
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
  }

  public function press($html, $config = [])
  {

  }

  public function pressMarkdown($markdown, $config = [])
  {

  }
}
