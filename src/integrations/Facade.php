<?php namespace EFrane\Letterpress\Facades;

interface Facade
{
  public function __construct();
  public function __get($property);
  public function __set($property, $value);
  public function __call($method, $args);
}
