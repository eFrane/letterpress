<?php namespace EFrane\Letterpress\Markup\RichMedia;

use DOMNode;
use EFrane\Letterpress\LetterpressException;
use EFrane\Letterpress\Markup\LinkModifier;
use EFrane\Letterpress\Markup\RecursiveModifier;
use Embed\Adapters\AdapterInterface;
use Embed\Embed;

abstract class MediaModifier extends RecursiveModifier
{
    /**
     * @var string regular expression for matching links
     */
    protected $linkPattern = '/.*/';

    /**
     * @var \EFrane\Letterpress\Markup\LinkModifier
     */
    protected $linkModifier = null;

    /**
     * @var Repository
     */
    protected $repository = null;

    public function __construct()
    {
        $this->linkModifier = new LinkModifier([&$this, 'enhanceMediaElement']);

        $this->setLinkPattern();
    }

    abstract protected function setLinkPattern();

    // TODO: implement media modification in pure text content, i.e. BBCode syntax

    /**
     * @return null
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param Repository $repository
     */
    public function setRepository(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function modify(\DOMDocumentFragment $fragment)
    {
        $this->linkModifier->setDocument($fragment->ownerDocument);

        return parent::modify($fragment);
    }

    public function candidateCheck(DOMNode $candidate)
    {
        /* @var $candidate \DOMElement */
        return $this->linkModifier->candidateCheck($candidate)
        && preg_match($this->linkPattern, $candidate->getAttribute('href'));
    }

    public function candidateModify(DOMNode $parent, DOMNode $candidate)
    {
        $this->linkModifier->candidateModify($parent, $candidate);
    }

    abstract public function enhanceMediaElement($url, \DOMDocument $doc);

    /**
     * @param $url
     * @return \EFrane\Letterpress\Markup\RichMedia\Lookup
     **/
    protected function lookup($url)
    {
        if (parse_url($url, PHP_URL_SCHEME) === null) {
            // if no url scheme was given, force https
            $url = sprintf('https://%s', $url);
        }

        $lookup = $this->repository->refreshLookup($url, function () use ($url) {
            try {
                $adapter = Embed::create($url);
            } catch (\Exception $e) {
                // wrap exception
                throw new LetterpressException($e);
            }

            if ($adapter instanceof AdapterInterface) {
                return $adapter;
            } else {
                throw new LetterpressException('Failed to resolve embed for url: ' . $url);
            }
        });

        return $lookup;
    }
}