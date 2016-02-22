<?php

namespace EFrane\Letterpress\Processing;

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Integrations\ParsedownFactory;

class Markdown
{
    /* @var $parsedown \Parsedown */
    protected static $parsedown = null;

    public static function run($content, $force = false)
    {
        $output = $content;

        if (Config::get('letterpress.markdown.enabled') || $force) {
            $escaper = new Escaper('/(?P<bbcode>\[.+?\].+\[\/.+?\])/', 'bbcode');

            $output = $escaper->escape($output);

            if (!is_a(static::$parsedown, \Parsedown::class)) {
                static::$parsedown = ParsedownFactory::create();
            }

            $output = static::$parsedown->parse($output);
            $output = $escaper->replace($output);
        }

        return $output;
    }
}
