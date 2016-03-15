<?php namespace EFrane\Letterpress\Markup\RichMedia;

// TODO: make this cacheable
use EFrane\Letterpress\LetterpressException;

/**
 * Class Repository
 *
 * @package EFrane\Letterpress\Markup\RichMedia
 **/
class Repository
{
    /**
     * @var \Illuminate\Support\Collection
     **/
    protected $lookups = null;

    /**
     * Repository constructor.
     *
     * @param LookupInterface[] $lookups
     */
    public function __construct(array $lookups = [])
    {
        $this->lookups = collect($lookups);
    }

    /**
     * @param bool $asArray
     * @return array|static
     **/
    public function getStoredUrls($asArray = false)
    {
        $keys = $this->lookups->keys();

        if ($asArray) {
            return $keys->toArray();
        } else {
            return $keys;
        }
    }

    /**
     * @param string $url
     * @param \Closure $refresh
     * @return \EFrane\Letterpress\Markup\RichMedia\Lookup
     * @throws LetterpressException
     **/
    public function refreshLookup($url, \Closure $refresh)
    {
        $lookup = $this->getLookup($url);

        if (!is_a($lookup, LookupInterface::class)) {
            try {
                $lookup = new Lookup($url, $refresh());
            } catch (\Exception $e) {
                if ($e instanceof LetterpressException) {
                    throw $e;
                } else {
                    throw new LetterpressException($e);
                }
            }

            $this->addLookup($lookup);
        }

        return $lookup;
    }

    /**
     * @param string $url
     * @return LookupInterface
     **/
    public function getLookup($url)
    {
        return $this->lookups->get($url);
    }

    /**
     * @param \EFrane\Letterpress\Markup\RichMedia\LookupInterface $lookup
     **/
    public function addLookup(LookupInterface $lookup)
    {
        $this->lookups->put($lookup->getUrl(), $lookup);
    }
}
