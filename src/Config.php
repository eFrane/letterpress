<?php namespace EFrane\Letterpress;

use Illuminate\Filesystem\Filesystem;

use Illuminate\Config\FileLoader;
use Illuminate\Config\Repository;

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

  private function __clone() {}

  public static function get($identifier = null, $default = null)
  {
    if (self::$instance === null)
    {
      throw new \RuntimeException("Config must be initialized before usage.");
    }

    return self::$instance->repository->get($identifier, $default);
  }
}
