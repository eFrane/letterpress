<?php namespace EFrane\Letterpress\Microtypography;

use JoliTypo\StateBag;
use JoliTypo\FixerInterface;
use JoliTypo\LocaleAwareFixerInterface;

/**
 * Format numbers according to the formal German number formatting
 * style, e.g. insert a dot for every block of thousands (1.234.567)
 * and use the comma as separator for decimals (1.234,567)
 **/
class GermanNumbers implements FixerInterface, LocaleAwareFixerInterface
{
  protected $locale;

  public function __construct($locale)
  {
    $this->locale = $locale;
  }
  
  public function setLocale($locale)
  {
    $this->locale = $locale;
  }

  public function fix($content, StateBag $state_bag = null)
  {
    if (preg_match('/[0-9,.]+/', $content))
    {
      return $this->numberFormat($content);
    }

    return $content;
  }

  protected function numberFormat($number)
  {
    // check for decimal point / comma
    $dotPosition = strlen($number) - 1;
    if (preg_match('/(,|\.)/', strrev($number), $matches) == 1)
      $dotPosition = strrpos($matches[1], $number);

    return $dotPosition;

    $formattedNumber = substr($number, $dotPosition, strlen($number) - 1);
    for ($i = $dotPosition; $i >= 0; $i--)
    {
      
    }

    return $formattedNumber;
  }
}
