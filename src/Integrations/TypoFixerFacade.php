<?php

namespace EFrane\Letterpress\Integrations;

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
        $locale = Config::get('letterpress.locale');
        if ($this->validateLocale($locale)) {
            $this->locale = $locale;
        } else {
            throw new LetterpressException('Typography fixing requires a locale.');
        }

        // defaults
        if (Config::get('letterpress.microtypography.useDefaults')) {
            $this->fixers = Config::get('jolitypo.defaults');

            // locale additions
            $localeKey = sprintf('jolitypo.%s', $this->locale);
            if (Config::has($localeKey)) {
                $this->fixers = array_merge($this->fixers, Config::get($localeKey));
            }
        }

        // hyphenation
        if (Config::get('letterpress.microtypography.enableHyphenation')) {
            $this->fixers[] = 'Hyphen';
        }

        // user additions
        $this->fixers = array_merge($this->fixers, Config::get('letterpress.microtypography.additionalFixers'));

        if (count($this->fixers) === 0) {
            throw new LetterpressException('Typography fixing requires setting up fixers.');
        }

        $this->fixer = new Fixer($this->fixers);
        $this->fixer->setLocale($this->locale);
    }

    /**
     * @param $locale
     * @return bool
     **/
    protected function validateLocale($locale)
    {
        return is_string($locale) && preg_match('/[a-z]{2}_[A-Z]{2}/', $locale);
    }

    public function __get($property)
    {
        if (strpos($property, 'facade_') === 0) {
            $property = substr($property, 7);

            return $this->{$property};
        } else {
            return $this->fixer->{$property};
        }
    }

    public function __set($property, $value)
    {
        if (strpos($property, 'facade_') === 0) {
            $property = substr($property, 7);
            $this->{$property} = $value;
        } else {
            $this->fixer->{$property} = $value;
        }
    }

    public function __call($method, $args)
    {
        return call_user_func_array([&$this->fixer, $method], $args);
    }
}
