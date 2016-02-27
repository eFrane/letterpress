<?php

namespace EFrane\Letterpress\Embed\Worker;

/**
 * Handle YouTube embeds.
 *
 * The url matcher regular expression was taken from
 * http://stackoverflow.com/a/19377429/718752.
 *
 * @author Stefan Graupner <stefan.graupner@gmail.com>
 **/
class YouTubeWorker extends VideoEmbedWorker
{
    protected $bbcode = true;

    protected $urlRegex = '(http(s)?\:\/\/)?(www\.)?(youtube\.com|youtu\.?be)\/.+';
}
