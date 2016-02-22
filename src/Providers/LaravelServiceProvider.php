<?php namespace EFrane\Letterpress\Providers;

use EFrane\Letterpress\Config as LetterpressConfig;
use EFrane\Letterpress\Letterpress;

use Illuminate\Support\ServiceProvider;

class LaravelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/jolitypo.php'    => config_path('jolitypo.php'),
            __DIR__ . '/../../config/letterpress.php' => config_path('letterpress.php'),
        ]);
    }

    public function register()
    {
        $config['letterpress'] = config('letterpress');
        $config['jolitypo'] = config('jolitypo');

        LetterpressConfig::init($config);

        $this->app['letterpress'] = $this->app->share(function () {
            return new Letterpress;
        });

        $this->mergeConfigFrom(__DIR__ . '/../../config/jolitypo.php', 'jolitypo');
        $this->mergeConfigFrom(__DIR__ . '/../../config/letterpress.php', 'letterpress');
    }
}
