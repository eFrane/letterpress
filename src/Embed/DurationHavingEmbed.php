<?php

namespace EFrane\Letterpress\Embed;

class DurationHavingEmbed extends Embed
{
    /**
   * @var float Duration of the embed in seconds.
   **/
  protected $duration = 0;

    public function __construct($uri, $code, $duration)
    {
        $this->duration = $duration;

        parent::__construct($uri, $code);
    }
}
