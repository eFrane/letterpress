<?php namespace EFrane\Letterpress\Markup\RichMedia;

use EFrane\Letterpress\Markup\LinkModifier;
use EFrane\Letterpress\Markup\RecursiveModifier;

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

    public function candidateCheck(DOMNode $candidate)
    {
        /* @var $candidate \DOMElement */
        return $this->linkModifier->candidateCheck($candidate) && preg_match($this->linkPattern, $candidate->getAttribute('href'));
    }

    public function candidateModify(DOMNode $parent, DOMNode $candidate)
    {
        return $this->linkModifier->candidateModify($parent, $candidate);
    }

    abstract public function enhanceMediaElement($url, \DOMDocument $doc);
}