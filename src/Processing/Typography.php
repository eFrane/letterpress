<?php namespace EFrane\Letterpress\Processing;

use EFrane\Letterpress\Config;
use EFrane\Letterpress\Integrations\TypoFixerFacade;

class Typography implements Processor
{
    public static function run($content, $force = false)
    {
        $output = $content;

        if (Config::get('letterpress.microtypography.enabled') || $force) {
            $fixer = new TypoFixerFacade();
            $output = $fixer->facade_fixer->fix($output);
        }

        return $output;
    }
}