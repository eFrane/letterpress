<?php namespace EFrane\Letterpress\Markup\RichMedia;

use Embed\Adapters\AdapterInterface;

class Lookup
{
    protected $url = '';
    protected $adapter = null;

    /**
     * Lookup constructor.
     * @param string $url
     * @param null $adapter
     */
    public function __construct($url, AdapterInterface $adapter)
    {
        $this->url = $url;
        $this->adapter = $adapter;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return \Embed\Adapters\AdapterInterface|null
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
}