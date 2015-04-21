<?php namespace EFrane\Letterpress;

use Symfony\Component\Finder\Finder;
use Illuminate\Config\Repository;

/**
 *  Simple wrapper class for the Illuminate\Config component;
 **/
class Config
{
  protected static $originalConfigPath = '';
  protected static $instance = null;

  /**
   * @var Illuminate\Config\Repository|null
   *
   */
  protected $repository = null;

  protected function __construct(array $config)
  {
    $this->repository = new Repository($config);
  }

  private static function loadDefaultConfig()
  {
    $data = [];

    $files = Finder::create()->files()->name('*.php')->in('config')->depth(0);
    foreach ($files as $key => $path)
      $data[basename($key, '.php')] = require $path;

    return $data;
  }

  public static function init(array $config = [])
  {
    if (count($config) == 0)
      $config = static::loadDefaultConfig();

    static::$instance = new Config($config);
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
