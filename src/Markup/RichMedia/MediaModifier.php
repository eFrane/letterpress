<?php namespace EFrane\Letterpress\Markup\RichMedia;

use DOMNode;
use EFrane\Letterpress\Markup\LinkModifier;
use EFrane\Letterpress\Markup\RecursiveModifier;
use Embed\Adapters\AdapterInterface;
use Embed\Embed;
use Embed\Request;
use Embed\Url;

abstract class MediaModifier extends RecursiveModifier
{
    protected $linkPattern = '/.*/';
    protected $linkModifier = null;

    // TODO: implement media modification in pure text content?!

    public function __construct()
    {
        $this->linkModifier = new LinkModifier([&$this, 'enhanceMediaElement']);

        $this->setLinkPattern();
    }

    abstract protected function setLinkPattern();

    public function modify(\DOMDocumentFragment $fragment)
    {
        $this->linkModifier->setDocument($fragment->ownerDocument);

        return parent::modify($fragment);
    }

    public function candidateCheck(DOMNode $candidate)
    {
        /* @var $candidate \DOMElement */
        return $this->linkModifier->candidateCheck($candidate) && preg_match($this->linkPattern, $candidate->getAttribute('href'));
    }

    public function candidateModify(DOMNode $parent, DOMNode $candidate)
    {
        $this->linkModifier->candidateModify($parent, $candidate);
    }

    protected function getOEmbedAdapter($url) {
        $request = new Request(new Url($url));

        /**
         * @var $embed AdapterInterface
         */
        $embed = Embed::create($request);

        return $embed;
    }

    abstract public function enhanceMediaElement($url, \DOMDocument $doc);
}