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

    public function addLookup(Lookup $lookup) {
        $this->lookups->put($lookup->getUrl(), $lookup);
    }

    public function getLookup($url) {
        return $this->lookups->get($url);
    }
}