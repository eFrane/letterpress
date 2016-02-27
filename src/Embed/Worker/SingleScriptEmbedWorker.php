<?php

namespace EFrane\Letterpress\Embed\Worker;

use DOMDocumentFragment;
use EFrane\Letterpress\Embed\Embed;
use Embed\Adapters\AdapterInterface;

abstract class SingleScriptEmbedWorker extends BaseEmbedWorker
{
    protected static $instances = 0;

    public function __construct()
    {
        static::$instances++;
    }

    public function apply(AdapterInterface $adapter)
    {
        $code = $this->importCode($this->doc, $adapter->getCode());

        if (static::$instances > 1) {
            $code = $this->removeScriptTag($code);
        }

        return new Embed($adapter->getUrl(), $code);
    }

    protected function removeScriptTag(DOMDocumentFragment $code)
    {
        // TODO: implement removal of additional script tags
        return $code;
    }
}
