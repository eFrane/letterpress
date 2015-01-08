<?php namespace EFrane\Letterpress\Providers;

use EFrane\Letterpress\Letterpress;
use EFrane\Letterpress\Config as LetterpressConfig;

use Illuminate\Support\ServiceProvider;
use \Illuminate\Foundation\AliasLoader;

class LaravelServiceProvider extends ServiceProvider
{
  protected $defer = true;

  public function boot()
  {
    $this->package('efrane/letterpress');
  }

  public function register()
  {
    LetterpressConfig::init(app_path().'/config/packages/efrane/letterpress');

    $this->app['letterpress'] = $this->app->share(function ($app) {
      $letterpress = new Letterpress;
      return $letterpress;
    });

    $this->app->bind('LetterpressConfig', 'EFrane\\Letterpress\\Config');

    $this->app->booting(function()
    {
      $loader = AliasLoader::getInstance();
      $loader->alias('Letterpress', 'EFrane\\Letterpress\\Providers\\LaravelFacade');
    });
  }

  public function provides()
  {
    return ['letterpress'];
  }
}
