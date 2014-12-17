<?php namespace EFrane\Letterpress\Facades;

use EFrane\Letterpress\Config;
use JoliTypo\Fixer;

class TypoFixerFacade implements Facade
{
  protected $fixer = null;
  protected $fixers = null;

  public function __construct()
  {
    if (!Config::get('letterpress.fixers'))
    {
    }
  }
}
