<?php

namespace EFrane\Letterpress\Integrations;

use EFrane\Letterpress\Config;
use EFrane\Letterpress\LetterpressException;

class ParsedownFactory implements Factory
{
    /**
     * @return \Parsedown
     */
    public static function create()
    {
        $class = '\Parsedown';

        if (Config::get('letterpress.markdown.useMarkdownExtra')) {
            if (!class_exists('\ParsedownExtra')) {
                throw new LetterpressException('Enabling MarkdownExtra requires ParsedownExtra to be installed.');
            }
            $class = '\ParsedownExtra';
        }

        /* @var $instance \Parsedown */
        $instance = new $class();

        $instance->setBreaksEnabled(Config::get('letterpress.markdown.enableLineBreaks'));
        $instance->setMarkupEscaped(!Config::get('letterpress.markdown.escapeMarkup'));

        return $instance;
    }
}
