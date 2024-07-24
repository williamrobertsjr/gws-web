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
use function preg_match;

class Pattern extends MatcherAbstract
{
    /**
     * Return a string representation of this Matcher
     *
     * @return string
     */
    public function __toString()
    {
        return '<Pattern>';
    }

    /**
     * Check if the actual value matches the expected pattern.
     *
     * @template TMixed
     *
     * @param TMixed $actual
     *
     * @return bool
     */
    public function match(&$actual)
    {
        return preg_match($this->_expected, (string) $actual) >= 1;
=======
class Pattern extends MatcherAbstract
{
    /**
     * Check if the actual value matches the expected pattern.
     *
     * @param mixed $actual
     * @return bool
     */
    public function match(&$actual)
    {
        return preg_match($this->_expected, (string) $actual) >= 1;
    }

    /**
     * Return a string representation of this Matcher
     *
     * @return string
     */
    public function __toString()
    {
        return '<Pattern>';
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }
}
