<?php

namespace EFrane\Letterpress\Microtypography;

use JoliTypo\FixerInterface;
use JoliTypo\LocaleAwareFixerInterface;
use JoliTypo\StateBag;

/**
 * Format numbers according to the formal German number formatting
 * style, e.g. insert a dot for every block of thousands (1.234.567)
 * and use the comma as separator for decimals (1.234,567).
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
        if (preg_match('/[0-9,.]+/', $content)) {
            return $this->numberFormat($content);
        }

        return $content;
    }

    protected function numberFormat($number)
    {
        preg_match('/([0-9 ]+)(\.|,)?([0-9]+)?/', $number, $parts);

        if (isset($parts[3])) {
            return number_format(floatval($number), strlen($parts[3]), ',', ' ');
        } else {
            return number_format(floatval($number), 0, ',', ' ');
        }
    }
}
