<?php namespace EFrane\Letterpress\Embeds;

use HTML5;
use Embed\Adapters\AdapterInterface;

use EFrane\Letterpress\Config;
use EFrane\Letterpress\LetterpressException;

class VideoEmbed extends BaseEmbed
{
  use \EFrane\Letterpress\Markup\DOMManipulation;

  public function apply(AdapterInterface $adapter)
  {
    $code = $adapter->getCode();

    if (Config::get('letterpress.media.enableResponsiveIFrames'))
    {
      return $this->responsiveIFrame($code);
    } else
    {
      return $this->importCode($this->doc, $code);
    }
  }
  
  protected function responsiveIFrame($frame)
  {
    // http://stackoverflow.com/questions/11122249/scale-iframe-css-width-100-like-an-image
    $fragment = $this->importCode('<div class="iframe img-responsive"/>');
    $rootNode = $fragment->firstChild;

    $img = $this->createTag($this->doc, 'img', null, [
      'class'  => 'ratio', 
      'src'    => '//placehold.it/16x9&text=+',
      'width'  => 16,
      'height' => 9
    ]);

    $rootNode = $rootNode->appendChild($img);

    $frame = $this->importCode($this->doc, $frame);

    $this->renameAttribute($frame->firstChild, 'width', 'data-width');
    $this->renameAttribute($frame->firstChild, 'height', 'data-height');

    $rootNode = $rootNode->appendChild($frame);

    return $fragment;
  }

  protected function textOnly(AdapterInterface $adapater)
  {
    $fragment = $this->importCode('<div />');
    $rootNode = $fragment->firstChild;

    $title = $this->createTag($this->doc, 'h1', $adapter->getTitle());
    $rootNode = $rootNode->appendChild($title);
    
    if (Config::get('letterpress.media.videoEmbedMode') == 'text')
    {
      $description = $this->createTag($this->doc, 'div', $adapter->getDescription())
      $rootNode = $rootNode->appendChild($description);
    }

    return $fragment;
  }

  protected function image(AdapterInterface $adapter)
  {
    $imageFragment = $this->importCode('<img />');
    $rootNode = $imageFragment->firstChild;

    $this->setAttributes($rootNode, [
      'src' => $adapter->getImage(),
      'width' => $adapter->getImageWidth(),
      'height' => $adapter->getImageHeight()
    ]);

    $textFragment = $this->textOnly($adapter);

    return $this->concatenateFragments($this->doc, [$imageFragment, $textFragment]);
  }
}
