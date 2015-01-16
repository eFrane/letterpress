<?php namespace EFrane\Letterpress\Embeds;

use HTML5;
use Embed\Adapters\AdapterInterface;

use EFrane\Letterpress\Config;
use EFrane\Letterpress\LetterpressException;

class VideoEmbed extends BaseEmbed
{
  protected $adapter = null;

  public function apply(AdapterInterface $adapter)
  {
    $this->adapter = $adapter;

    switch (Config::get('letterpress.media.videoEmbedMode'))
    {
      case 'frame': return $this->embedFrame(); break;
      case 'link':  return $this->embedLink();  break;
      case 'text':  return $this->embedText();  break;
      case 'image': return $this->embedImage(); break;
    }

    return null;
  }

  protected function embedFrame()
  {
    $frame = $this->importCode($this->doc, $this->adapter->getCode());

    if (!Config::get('letterpress.media.enableResponsiveIFrames'))
      return $frame;

    // http://stackoverflow.com/questions/11122249/scale-iframe-css-width-100-like-an-image
    $fragment = $this->importCode($this->doc, '<div class="iframe img-responsive"/>');
    $rootNode = $fragment->firstChild;

    $img = $this->createTag($this->doc, 'img', null, [
      'class'  => 'ratio', 
      'src'    => '//placehold.it/16x9&text=+',
      'width'  => 16,
      'height' => 9
    ]);

    $rootNode = $rootNode->appendChild($img);

    $this->renameAttribute($frame->firstChild, 'width', 'data-width');
    $this->renameAttribute($frame->firstChild, 'height', 'data-height');

    $rootNode = $rootNode->appendChild($frame);

    return $fragment;
  }

  protected function embedLink()
  {
    // TODO
  }

  protected function embedText()
  {
    $fragment = $this->importCode('<div />');
    $rootNode = $fragment->firstChild;

    $title = $this->createTag($this->doc, 'h1', $this->adapter->getTitle());
    $rootNode = $rootNode->appendChild($title);
    
    if (Config::get('letterpress.media.videoEmbedMode') == 'text')
    {
      $description = $this->createTag($this->doc, 'div', $this->adapter->getDescription());
      $rootNode = $rootNode->appendChild($description);
    }

    return $fragment;
  }

  protected function embedImage()
  {
    $imageFragment = $this->importCode('<img />');
    $rootNode = $imageFragment->firstChild;

    $this->setAttributes($rootNode, [
      'src' => $this->adapter->getImage(),
      'width' => $this->adapter->getImageWidth(),
      'height' => $this->adapter->getImageHeight()
    ]);

    return $this->concatenateFragments($this->doc, [$imageFragment, $this->embedText()]);
  }
}
