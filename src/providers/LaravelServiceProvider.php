<?php namespace EFrane\Letterpress\Providers;

use EFrane\Letterpress\Letterpress;
use EFrane\Letterpress\Config as LetterpressConfig;

use Illuminate\Support\ServiceProvider

class LaravelServiceProvider extends ServiceProvider
{
  protected $defer = true;

  public function register()
  {
    $this->app['letterpress'] = $this->app->share(function ($app) {
      $config = LetterpressConfig::init();
      $letterpress = new Letterpress($config);
      
      return $letterpress;
    });
  }
}
