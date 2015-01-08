<?php namespace EFrane\Letterpress\Embeds;

use HTML5;
use Embed\Adapters\AdapterInterface;

use EFrane\Letterpress\Config;
use EFrane\Letterpress\LetterpressException;

class VideoEmbed extends BaseEmbed
{
  public function apply(AdapterInterface $adapter)
  {
    $code = $adapter->getCode();

    if (strlen($code) === 0)
    {
      throw new LetterpressException("Failed to acquire basic embed code.");
    }

    if (Config::get('letterpress.markup.enableResponsiveIFrames'))
    {
      return $this->responsiveIFrame($code);
    } else
    {
      return HTML5::loadHTMLFragment($code);
    }
  }
  
  protected function responsiveIFrame($frame)
  {
    // http://stackoverflow.com/questions/11122249/scale-iframe-css-width-100-like-an-image
    $fragment =  HTML5::loadHTMLFragment('<div class="iframe img-responsive"/>');

    $img = $fragment->ownerDocument->createElement('img');
    $img->setAttribute('class', 'ratio');
    $img->setAttribute('src', '//placehold.it/16x9&text=+');
    $img->setAttribute('width', 16);
    $img->setAttribute('height', 9);

    $fragment->firstChild->appendChild($img);

    $frame = $fragment->ownerDocument->importNode(HTML5::loadHTMLFragment($frame), true);
    $fragment->firstChild->appendChild($frame);

    return $fragment;
  }
}
