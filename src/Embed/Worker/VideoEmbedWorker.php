<?php namespace EFrane\Letterpress\Embed\Worker;

use EFrane\Letterpress\Embed\DurationHavingEmbed;
use HTML5;
use Embed\Adapters\AdapterInterface;

use EFrane\Letterpress\Config;

class VideoEmbedWorker extends BaseEmbedWorker
{
  protected $adapter = null;

  public function apply(AdapterInterface $adapter)
  {
    $this->adapter = $adapter;

    $code = null;
    switch (Config::get('letterpress.media.videoEmbedMode'))
    {
      case 'frame': $code = $this->embedFrame(); break;
      case 'link':  $code = $this->embedLink();  break;
      case 'text':  $code = $this->embedText();  break;
      case 'image': $code = $this->embedImage(); break;
    }

    return new DurationHavingEmbed($adapter->getUrl(), $code, 0);
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
    $link = $this->importCode($this->doc, '<a />');
    $url = $this->adapter->getUrl();

    $link->firstChild->nodeValue = $url;
    $link->firstChild->setAttribute('href', $url);

    return $link;
  }

  protected function embedText()
  {
    $fragment = $this->importCode($this->doc, '<div />');
    $rootNode = $fragment->firstChild;

    $title = $this->createTag($this->doc, 'h1', $this->adapter->getTitle());
    $rootNode = $rootNode->appendChild($title);
    
    if (Config::get('letterpress.media.videoEmbedMode') == 'text')
    {
      $description = $this->createTag($this->doc, 'p', $this->adapter->getDescription());
      $rootNode = $rootNode->appendChild($description);
    }

    $rootNode = $rootNode->appendChild($this->embedLink());

    return $fragment;
  }

  // TODO: this seems to be not working for unexplainable reasons
  protected function embedImage()
  {
    $imageFragment = $this->importCode($this->doc, '<img />');
    $rootNode = $imageFragment->firstChild;

    $this->setAttributes($rootNode, [
      'src' => $this->adapter->image,
      'width' => $this->adapter->imageWidth,
      'height' => $this->adapter->imageHeight
    ]);

    ldd(HTML5::saveHTML($imageFragment));

    return $this->concatenateFragments($this->doc, [$imageFragment, $this->embedText()]);
  }
}
