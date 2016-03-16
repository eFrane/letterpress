<?php namespace EFrane\Letterpress\Markup\RichMedia;

use DOMNode;
use EFrane\Letterpress\LetterpressException;
use EFrane\Letterpress\Markup\LinkModifier;
use EFrane\Letterpress\Markup\RecursiveModifier;
use Embed\Adapters\AdapterInterface;
use Embed\Embed;
use Masterminds\HTML5;

abstract class MediaModifier extends RecursiveModifier
{
    /**
     * @var string regular expression for matching links
     */
    protected $linkPattern = '/.*/';

    /**
     * @var string regular expression for matching BBCode tags
     */
    protected $tagPattern = '/.*/';

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
        $this->linkModifier = new LinkModifier([&$this, 'linkReplacer']);

        $this->setLinkPattern();
        $this->setTagPattern();
    }

    abstract protected function setLinkPattern();

    protected function setTagPattern()
    {
        $tagName = explode('\\', get_called_class());
        $tagName = strtolower($tagName[count($tagName) - 1]);

        $this->tagPattern = sprintf("/\[%s.*?\]%s\[\/%s\]/i", $tagName, $this->linkPattern, $tagName);
    }

    // TODO: implement media modification in pure text content, i.e. BBCode syntax

    /**
     * @return Repository
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

    public function linkReplacer($url, \DOMDocument $doc)
    {
        $lookup = $this->lookup($url);

        if ($lookup instanceof LookupInterface) {
            $code = $lookup->getFrameSource();

            $fragment = (new HTML5())->loadHTMLFragment($code);
            $imported = $doc->importNode($fragment->firstChild, true);

            return $imported;
        } else {
            return null;
        }
    }

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

    public function bbCodeReplacer($code, $matches)
    {

    }
}
