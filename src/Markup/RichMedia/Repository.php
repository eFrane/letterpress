<?php namespace EFrane\Letterpress\Markup\RichMedia;

// TODO: make this cacheable
class Repository
{
    protected $lookups = null;

    /**
     * Repository constructor.
     * @param null $lookups
     */
    public function __construct(array $lookups = [])
    {
        $this->lookups = collect($lookups);
    }

    public function getStoredUrls($asArray = false)
    {
        $keys = $this->lookups->keys();

        if ($asArray) {
            return $keys->toArray();
        } else {
            return $keys;
        }
    }

    public function refreshLookup($url, \Closure $refresh)
    {
        $lookup = $this->getLookup($url);

        if (!is_a($lookup, LookupInterface::class)) {
            $lookup = new Lookup($url, $refresh());
            $this->addLookup($lookup);
        }

        return $lookup;
    }

    public function getLookup($url)
    {
        return $this->lookups->get($url);
    }

    public function addLookup(LookupInterface $lookup)
    {
        $this->lookups->put($lookup->getUrl(), $lookup);
    }
}