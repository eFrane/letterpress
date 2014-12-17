<?php namespace EFrane\Letterpress

class Letterpress
{
  protected $config = null;

  public function __construct(Config $config)
  {
    $this->config = $config;
  }

  public function press($html, $config = [])
  {

  }

  public function pressMarkdown($markdown, $config = [])
  {

  }
}
