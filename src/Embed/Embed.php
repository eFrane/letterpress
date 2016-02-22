<?php

namespace EFrane\Letterpress\Embed;

use DOMDocumentFragment;

class Embed implements \ArrayAccess
{
    protected $uri = '';
    protected $code = '';

    public function __construct($uri, DOMDocumentFragment $code)
    {
        $this->uri = $uri;
        $this->code = $code;
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }
    }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Whether a offset exists.
   *
   * @link http://php.net/manual/en/arrayaccess.offsetexists.php
   *
   * @param mixed $offset <p>
   * An offset to check for.
   * </p>
   *
   * @return bool true on success or false on failure.
   * </p>
   * <p>
   * The return value will be casted to boolean if non-boolean was returned.
   */
  public function offsetExists($offset)
  {
      return property_exists($this, $offset);
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to retrieve.
   *
   * @link http://php.net/manual/en/arrayaccess.offsetget.php
   *
   * @param mixed $offset <p>
   * The offset to retrieve.
   * </p>
   *
   * @return mixed Can return all value types.
   */
  public function offsetGet($offset)
  {
      return $this->{$offset};
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to set.
   *
   * @link http://php.net/manual/en/arrayaccess.offsetset.php
   *
   * @param mixed $offset <p>
   * The offset to assign the value to.
   * </p>
   * @param mixed $value <p>
   * The value to set.
   * </p>
   *
   * @return void
   */
  public function offsetSet($offset, $value)
  {
      throw new \RuntimeException('Operation not allowed.');
  }

  /**
   * (PHP 5 &gt;= 5.0.0)<br/>
   * Offset to unset.
   *
   * @link http://php.net/manual/en/arrayaccess.offsetunset.php
   *
   * @param mixed $offset <p>
   * The offset to unset.
   * </p>
   *
   * @return void
   */
  public function offsetUnset($offset)
  {
      throw new \RuntimeException('Operation not allowed.');
  }
}
