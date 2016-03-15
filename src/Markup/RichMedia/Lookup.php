<?php namespace EFrane\Letterpress\Markup\RichMedia;

use Embed\Adapters\AdapterInterface;

class Lookup implements LookupInterface
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

    public function getFrameSource()
    {
        return $this->adapter->getCode();
    }

    public function getType()
    {
        return $this->adapter->getType();
    }

    public function hasDuration()
    {
        return in_array($this->getType(), ['video']);
    }

    public function getDuration()
    {
        if ($this->hasDuration()) {
            // TODO: somehow fetch item duration
            // NOTE: There might not be a generic way for this which would be a problem
            return -1;
        } else {
            return 0;
        }
    }
}