<?php

namespace EFrane\Letterpress\Processing;

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Markup\MarkupProcessor;

class Markup implements Processor
{
    public static $embedRepository = null;

    public static function run($content, $force = false)
    {
        $output = $content;

        if (Config::get('letterpress.markup.enabled') || $force) {
            $markup = new MarkupProcessor();
            $output = $markup->process($content);

            static::$embedRepository = $markup->getEmbedRepository();
        }

        return $output;
    }
}
