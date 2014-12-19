<?php namespace EFrane\Letterpress\Facades;

use EFrane\Letterpress\Config;
use EFrane\Letterpress\LetterpressException;

use JoliTypo\Fixer;

class TypoFixerFacade implements Facade
{
  protected $fixer = null;

  protected $locale = '';
  protected $fixers = [];

  public function __construct()
  {
    // get locale
    if (($locale = Config::get('letterpress.locale')))
    {
      $this->locale = $locale;
    } else
    {
      throw new LetterpressException("Typography fixing requires a locale.");
    }

    // defaults
    if (Config::get('letterpress.microtypography.useDefaults'))
    {
      $this->fixers = Config::get('jolitypo.defaults');

      // locale additions
      $localeKey = sprintf('jolitypo.%s', $ths->locale);
      if (Config::has($localeKey))
      {
        $this->fixers = array_merge($this->fixers, Config::get($localeKey));
      }
    }    

    // user additions
    $this->fixers = array_merge($this->fixers, Config::get('letterpress.fixers'));

    if (count($this->fixers) === 0)
      throw new LetterpressException("Typography fixing requires setting up fixers.");

    $this->fixer = new Fixer($this->fixers);
    $this->fixer->setLocale($this->locale);
  }

  public function __get($property)
  {
    if (strrpos($property, 'facade_') === 0)
    {
      return $this->{$property};
    } else
    {
      return $this->fixer->{$property};
    }
  }

  public function __set($property, $value)
  {
    if (strrpos($property, 'facade_') === 0)
    {
      $this->{$property} = $value;
    } else
    {
      $this->fixer->{$property} = $value;
    }
  }

  public function __call($method, $args)
  {
    return call_user_func_array([&$this->fixer, $method], $args);
  }
}
