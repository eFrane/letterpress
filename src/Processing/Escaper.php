<?php namespace EFrane\Letterpress\Processing;

/**
 * Escaper - Save text patterns from processing by hiding them
 *
 * Due to the nature of multi-formatted input texts,
 * it is sometimes necessary to avoid parts of the input
 * string being processed by a certain processor, e.g. BBCode-style
 * tags must be escaped before running a markdown processor
 * since markdown uses a similar syntax for links and such.
 *
 * Usage:
 *
 * ```php
 * $escaper = new Escaper('regularexpression');
 * $escaped_content = $escaper->escape($content);
 *
 * // ... process escaped content ...
 *
 * $replaced = $escaper->replace($processed);
 *
 * ```
 *
 * NOTE: This is, by design, not thread safe. Meaning that the module
 *       expects `$processed` to be some modified version of
 *       `$escaped_content`, otherwise it will not work.
 *
 * @package EFrane\Letterpress\Processing
 */
class Escaper
{
    protected $pattern = '';
    protected $matchIdentifier = 1;

    protected $replacements = [];

    public function __construct($pattern, $matchIdentifier = 1)
    {
        $this->pattern = $pattern;
        $this->matchIdentifier = 1;
    }

    public function escape($content)
    {
        preg_match_all($this->pattern, $content, $matches);
        foreach ($matches[$this->matchIdentifier] as $match)
        {
            $escaped = sha1($match);
            $content = str_replace($match, $escaped, $content);

            $this->replacements[$escaped] = $match;
        }

        return $content;
    }

    public function replace($processed)
    {
        return str_replace(
            array_keys($this->replacements),
            array_values($this->replacements),
            $processed
        );
    }
}