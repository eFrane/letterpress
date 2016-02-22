<?php namespace EFrane\Letterpress;

use Illuminate\Config\Repository;
use Symfony\Component\Finder\Finder;

/**
 *  Simple wrapper class for the Illuminate\Config component;
 **/
class Config
{
    /**
     * @var string
     */
    protected static $originalConfigPath = '';

    /**
     * @var Config
     */
    protected static $instance = null;

    /**
     * @var \Illuminate\Config\Repository
     */
    protected $repository = null;

    protected function __construct(array $config)
    {
        $this->repository = new Repository($config);
    }

    public static function reset($withoutInit = false)
    {
        self::$instance = null;
        if (!$withoutInit) return self::init();
    }

    public static function init(array $config = [])
    {
        if (count($config) == 0)
            $config = static::loadDefaultConfig();

        static::$instance = new Config($config);

        return static::$instance;
    }

    public static function loadDefaultConfig($configPath = 'config')
    {
        $data = [];

        $files = Finder::create()->files()->name('*.php')->in($configPath)->depth(0);
        foreach ($files as $key => $path)
            $data[basename($key, '.php')] = require $path;

        return $data;
    }

    public static function get($identifier = null, $default = null)
    {
        self::checkInitialized();

        return self::$instance->repository->get($identifier, $default);
    }

    public static function has($identifier = null, $value = null)
    {
        self::checkInitialized();

        return self::$instance->repository->has($identifier, $value);
    }

    public static function apply($additionalConfig = [])
    {
        $oldValues = [];
        foreach ($additionalConfig as $identifier => $value) {
            if (!is_string($identifier))
                throw new \LogicException('Identifier must be string.');

            $oldValues[$identifier] = Config::set($identifier, $value);
        }

        return $oldValues;
    }

    /**
     * @param string $identifier
     * @param mixed $value new config value for identifier
     * @return mixed old config value for identifier
     */
    public static function set($identifier = null, $value = null)
    {
        self::checkInitialized();

        $oldValue = self::$instance->repository->get($identifier);
        self::$instance->repository->set($identifier, $value);

        return $oldValue;
    }

    protected static function checkInitialized()
    {
        if (self::$instance === null)
            throw new \RuntimeException('Config must be initialized before usage.');
    }

    public static function instance() {
        return static::$instance;
    }

    private function __clone()
    {
    }
}
