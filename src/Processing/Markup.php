<?php

namespace EFrane\Letterpress\Processing;

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Markup\MarkupProcessor;

class Markup implements Processor
{
    public static function run($content, $force = false)
    {
        $output = $content;

        if (Config::get('letterpress.markup.enabled') || $force) {
            $markup = new MarkupProcessor();
            $output = $markup->process($content);
        }

        return $output;
    }
}
