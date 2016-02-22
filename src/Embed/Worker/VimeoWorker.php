<?php

namespace EFrane\Letterpress\Embed\Worker;

class VimeoWorker extends VideoEmbedWorker
{
    protected $bbcode = true;
    protected $urlRegex = '(http(s)?:\/\/)?(www\.)?vimeo\.com\/(\d+)';
}
