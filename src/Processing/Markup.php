<?php

namespace EFrane\Letterpress\Processing;

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Markup\MarkupProcessor;
use EFrane\Letterpress\Markup\RichMedia\Repository;

class Markup implements Processor
{
    public static function run($content, $force = false)
    {
        $output = $content;

        if (Config::get('letterpress.markup.enabled') || $force) {
            // TODO: implement a way of injecting cached media repositories
            $markup = new MarkupProcessor(new Repository());
            $output = $markup->process($content);
        }

        return $output;
    }
}
