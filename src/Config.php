<?php

namespace EFrane\Letterpress;

use Illuminate\Config\Repository;
use Symfony\Component\Finder\Finder;

/**
 *  Simple wrapper class for the Illuminate\Config component;.
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

        if (!$withoutInit) {
            return self::init();
        }
    }

    public static function init(array $config = [])
    {
        if (count($config) == 0) {
            $configPath = static::getDefaultConfigPath();

            $config = static::loadDefaultConfig($configPath);
        }

        static::$instance = new self($config);

        return static::$instance;
    }

    public static function getDefaultConfigPath()
    {
        $configPath = 'config';

        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $configPath;
    }

    public static function loadDefaultConfig($configPath)
    {
        $data = [];

        $files = Finder::create()->files()->name('*.php')->in($configPath)->depth(0);
        foreach ($files as $key => $path) {
            $data[basename($key, '.php')] = require $path;
        }

        return $data;
    }

    public static function isInitialized()
    {
        return static::$instance instanceof self;
    }

    public static function get($identifier = null, $default = null)
    {
        self::checkInitialized();

        $value = self::$instance->repository->get($identifier, $default);

        if ($identifier === 'letterpress.locale'
            && !(is_string($value) && preg_match('/[a-z]{2,}_[A-Z]{2,}/', $value))
        ) {
            throw new LetterpressException('Invalid locale.');
        }

        return $value;
    }

    protected static function checkInitialized()
    {
        if (self::$instance === null) {
            throw new LetterpressException('Config must be initialized before usage.');
        }
    }

    public static function has($identifier = null)
    {
        self::checkInitialized();

        return self::$instance->repository->has($identifier);
    }

    public static function apply($additionalConfig = [])
    {
        $oldValues = [];
        foreach ($additionalConfig as $identifier => $value) {
            if (!is_string($identifier)) {
                throw new \LogicException('Identifier must be string.');
            }

            $oldValues[$identifier] = self::set($identifier, $value);
        }

        return $oldValues;
    }

    /**
     * @param string $identifier
     * @param mixed $value new config value for identifier
     *
     * @return mixed old config value for identifier
     */
    public static function set($identifier = null, $value = null)
    {
        self::checkInitialized();

        $oldValue = self::$instance->repository->get($identifier);
        self::$instance->repository->set($identifier, $value);

        return $oldValue;
    }

    public static function instance()
    {
        return static::$instance;
    }

    public function __clone()
    {
        throw new \RuntimeException('Cloning Letterpress\\Config is forbidden.');
    }
}
