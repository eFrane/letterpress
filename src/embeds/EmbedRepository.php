<?php namespace EFrane\Letterpress\Embeds;

use DOMDocumentFragment;
use \HTML5;

class EmbedRepository
{
  protected $embeds = [];
  protected $html;

  public function __construct(array $embeds = [])
  {
    foreach ($embeds as $embedCandidate)
    {
      if (class_exists($embedCandidate))
      {
        try
        {
          $embedInstance = new $embedCandidate;
          if ($embedInstance instanceof EFrane\Letterpress\Embeds\Embed)
            $this->embeds[] = $embedInstance;
        } catch(\Exception $e)
        {
          continue;
        }
      }
    }
  }

  public function apply(DOMDocumentFragment $fragment)
  {
    foreach ($this->embeds as $embed)
    {
      if ($embed->test(HTML5::saveHTML($fragment)))
        $embed->apply($fragment, $template);
    };
  }
}
