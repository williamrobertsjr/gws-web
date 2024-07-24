<?php

/**
 * Mockery (https://docs.mockery.io/)
 *
 * @copyright https://github.com/mockery/mockery/blob/HEAD/COPYRIGHT.md
<<<<<<< HEAD
 * @license https://github.com/mockery/mockery/blob/HEAD/LICENSE BSD 3-Clause License
 * @link https://github.com/mockery/mockery for the canonical source repository
=======
 * @license   https://github.com/mockery/mockery/blob/HEAD/LICENSE BSD 3-Clause License
 * @link      https://github.com/mockery/mockery for the canonical source repository
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
 */

namespace Mockery\Matcher;

<<<<<<< HEAD
use function array_replace_recursive;
use function implode;
use function is_array;

class Subset extends MatcherAbstract
{
    private $expected;

=======
class Subset extends MatcherAbstract
{
    private $expected;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    private $strict = true;

    /**
     * @param array $expected Expected subset of data
<<<<<<< HEAD
     * @param bool  $strict   Whether to run a strict or loose comparison
=======
     * @param bool $strict Whether to run a strict or loose comparison
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct(array $expected, $strict = true)
    {
        $this->expected = $expected;
        $this->strict = $strict;
    }

    /**
<<<<<<< HEAD
     * Return a string representation of this Matcher
     *
     * @return string
     */
    public function __toString()
    {
        return '<Subset' . $this->formatArray($this->expected) . '>';
=======
     * @param array $expected Expected subset of data
     *
     * @return Subset
     */
    public static function strict(array $expected)
    {
        return new static($expected, true);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * @param array $expected Expected subset of data
     *
     * @return Subset
     */
    public static function loose(array $expected)
    {
        return new static($expected, false);
    }

    /**
     * Check if the actual value matches the expected.
     *
<<<<<<< HEAD
     * @template TMixed
     *
     * @param TMixed $actual
     *
=======
     * @param mixed $actual
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     * @return bool
     */
    public function match(&$actual)
    {
<<<<<<< HEAD
        if (! is_array($actual)) {
=======
        if (!is_array($actual)) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            return false;
        }

        if ($this->strict) {
            return $actual === array_replace_recursive($actual, $this->expected);
        }

        return $actual == array_replace_recursive($actual, $this->expected);
    }

    /**
<<<<<<< HEAD
     * @param array $expected Expected subset of data
     *
     * @return Subset
     */
    public static function strict(array $expected)
    {
        return new static($expected, true);
=======
     * Return a string representation of this Matcher
     *
     * @return string
     */
    public function __toString()
    {
        return '<Subset' . $this->formatArray($this->expected) . ">";
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * Recursively format an array into the string representation for this matcher
     *
<<<<<<< HEAD
=======
     * @param array $array
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     * @return string
     */
    protected function formatArray(array $array)
    {
        $elements = [];
        foreach ($array as $k => $v) {
            $elements[] = $k . '=' . (is_array($v) ? $this->formatArray($v) : (string) $v);
        }
<<<<<<< HEAD

        return '[' . implode(', ', $elements) . ']';
=======
        return "[" . implode(", ", $elements) . "]";
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }
}
