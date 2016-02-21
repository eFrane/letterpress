<?php namespace EFrane\Letterpress\Processing;

interface Processor
{
    public static function run($content, $force = false);
}