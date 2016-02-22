<?php

namespace EFrane\Letterpress;

use EFrane\Letterpress\Processing\Markdown;
use EFrane\Letterpress\Processing\Markup;
use EFrane\Letterpress\Processing\Typography;

/**
 * @author Stefan Graupner <stefan.graupner@gmail.com>
 **/
class Letterpress
{
    /**
     * Setup a new Letterpress instance.
     *
     * Please note that there will only be one configuration object used by all
     * letterpress instances. It is however always possible to override specific
     * or all config options on calls to `press()` &c.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        // check for initialized config
        try {
            Config::get('letterpress.locale');
        } catch (\RuntimeException $e) {
            throw new LetterpressException($e->getMessage());
        }

        $this->setup($config);
    }

    /**
     * @param array $config
     **/
    protected function setup($config = [])
    {
        // apply additional config
        if (count($config) > 0) {
            Config::apply($config);
        }
    }

    /**
     * This will return the last embed repository.
     *
     * The embed repository is overridden whenever the markup
     * processor runs with enabled embed processing.
     *
     * @return EFrane\Letterpress\Embeds\EmbedRepository
     **/
    public function getEmbedRepository()
    {
        return Markup::$embedRepository;
    }

    /**
     * @param $input
     * @param array $config
     *
     * @return mixed|string
     **/
    public function press($input, $config = [])
    {
        $this->setup($config);

        $output = $this->markdown($input);
        $output = $this->markup($output);
        $output = $this->typofix($output);

        return $output;
    }

    /**
     * @param $input
     * @param bool  $force
     * @param array $config
     *
     * @return mixed
     **/
    public function markdown($input, $force = false, $config = [])
    {
        $this->setup($config);

        return Markdown::run($input, $force);
    }

    /**
     * @param $input
     * @param bool  $force
     * @param array $config
     *
     * @return mixed
     **/
    public function markup($input, $force = false, $config = [])
    {
        $this->setup($config);

        return Markup::run($input, $force);
    }

    /**
     * @param $input
     * @param bool  $force
     * @param array $config
     *
     * @return mixed
     **/
    public function typofix($input, $force = false, $config = [])
    {
        $this->setup($config);

        return Typography::run($input, $force);
    }
}
