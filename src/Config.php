<?php namespace EFrane\Letterpress;

use Illuminate\Filesystem\Filesystem;

use Illuminate\Config\FileLoader;
use Illuminate\Config\Repository;

/**
 *  Simple wrapper class for the Illuminate\Config component;
 **/
class Config
{
  protected static $originalConfigPath = '';
  protected static $instance = null;

  protected $repository = null;

  protected function __construct($configPath)
  {
    if (!file_exists($configPath))
      throw new \RuntimeException('Path to config does not exist.');

    $loader = new FileLoader(new Filesystem, $configPath);
    $this->repository = new Repository($loader, null);
  }

  public static function init($configPath = '')
  {
    if (strlen(static::$originalConfigPath) == 0)
      static::$originalConfigPath = $configPath;

    if (strcmp($configPath, static::$originalConfigPath) === 0
    ||  strlen($configPath) == 0)
      $configPath = static::$originalConfigPath;

    static::$instance = new Config($configPath);
    return static::$instance;
  }

  public static function reset($withoutInit = false)
  {
    self::$instance = null;
    if (!$withoutInit) return self::init();
  }

  private function __clone() {}

  public static function get($identifier = null, $default = null)
  {
    if (self::$instance === null)
      throw new \RuntimeException("Config must be initialized before usage.");

    return self::$instance->repository->get($identifier, $default);
  }

  public static function set($identifier = null, $value = null)
  {
    if (self::$instance === null)
      throw new \RuntimeException('Config must be initialized before usage.'); 

    $oldValue = self::$instance->repository->get($identifier);
    self::$instance->repository->set($identifier, $value);
    return $oldValue;
  }

  public static function has($identifier = null, $value = null)
  {
    if (self::$instance === null)
      throw new \RuntimeException('Config must be initialized before usage.'); 

    return self::$instance->repository->has($identifier, $value);
  }

  public static function apply($additionalConfig = [])
  {
    $oldValues = [];
    foreach ($additionalConfig as $identifier => $value)
    {
      if (!is_string($identifier))
        throw new \LogicException('Identifier must be string.');

      $oldValues[$identifier] = Config::set($identifier, $value);
    }

    return $oldValues;
  }
}
