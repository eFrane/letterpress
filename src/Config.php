<?php namespace EFrane\Letterpress;

use Illuminate\Filesystem\Filesystem;

use Illuminate\Config\FileLoader;
use Illuminate\Config\Repository;

/**
 *  Simple wrapper class for the Illuminate\Config component;
 **/
class Config
{
  protected static $instance = null;
  protected $repository = null;

  protected function __construct($configPath)
  {
    // check for relative path
    if ($configPath[0] !== '/')
    {
      $configPath = dirname(__FILE__).DIRECTORY_SEPARATOR.$configPath;
    }

    $loader = new FileLoader(new Filesystem, $configPath);
    $this->repository = new Repository($loader, null);
  }

  public static function init($configPath = '../config/')
  {
    if (self::$instance === null)
    {
      self::$instance = new Config($configPath);
    }

    return self::$instance;
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
    {
      throw new \RuntimeException("Config must be initialized before usage.");
    }

    return self::$instance->repository->get($identifier, $default);
  }

  public static function set($identifier = null, $value = null)
  {
    if (self::$instance === null)
    {
      throw new \RuntimeException('Config must be initialized before usage.'); 
    }

    // TODO: implement option change notifications

    return self::$instance->repository->set($identifier, $value);
  }

  public static function has($identifier = null, $value = null)
  {
    if (self::$instance === null)
    {
      throw new \RuntimeException('Config must be initialized before usage.'); 
    }

    return self::$instance->repository->has($identifier, $value);
  }

  public static function apply($additionalConfig = [])
  {
    foreach ($additionalConfig as $identifier => $value)
    {
      if (!is_string($identifier))
      {
        throw new \LogicException('Identifier must be string.');
      } else
      {
        Config::set($identifier, $value);
      }
    }
  }
}
