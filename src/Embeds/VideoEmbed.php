<?php namespace EFrane\Letterpress\Embeds;

use HTML5;

/**
 * http://stackoverflow.com/questions/11122249/scale-iframe-css-width-100-like-an-image
 *
 * <div class="wrapper">
    <div class="h_iframe">
        <!-- a transparent image is preferable -->
        <img class="ratio" src="http://placehold.it/16x9"/>
        <iframe src="http://www.youtube.com/embed/WsFWhL4Y84Y" frameborder="0" allowfullscreen></iframe>
    </div>
    <p>Please scale the "result" window to notice the effect.</p>
</div>
 **/
class VideoEmbed extends BaseEmbed
{
  public function apply(Embed\Adapters\AdapterInterface $adapter)
  {
    $code = $adapter->getCode();
    if (Config::get('letterpress.media.enableResponsiveIFrames'))
    {
      return $this->responsiveIFrame($code);
    } else
    {
      return $code;
    }
  }
  
  protected function responsiveIFrame($frame)
  {
    $fragment =  HTML5::loadHTMLFragment('<div class="r-iframe"/>');

    $img = $fragment->ownerDocument->createElement('img');
    $img->setAttribute('class', 'ratio');
    $img->setAttribute('src', '//placehold.it/16x9&text=+');
    $img->setAttribute('width', 16);
    $img->setAttribute('height', 9);

    $fragment->appendChild($img);
    $fragment->appendChild(HTML5::loadHTMLFragment($frame));

    return HTML5::saveHTML($fragment);
  }
}
