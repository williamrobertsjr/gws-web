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
use function is_object;

=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
/**
 * @deprecated 2.0 Due to ambiguity, use PHPUnit equivalents
 */
class MustBe extends MatcherAbstract
{
    /**
<<<<<<< HEAD
     * Return a string representation of this Matcher
     *
     * @return string
     */
    public function __toString()
    {
        return '<MustBe>';
    }

    /**
     * Check if the actual value matches the expected.
     *
     * @template TMixed
     *
     * @param TMixed $actual
     *
=======
     * Check if the actual value matches the expected.
     *
     * @param mixed $actual
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     * @return bool
     */
    public function match(&$actual)
    {
<<<<<<< HEAD
        if (! is_object($actual)) {
=======
        if (!is_object($actual)) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            return $this->_expected === $actual;
        }

        return $this->_expected == $actual;
    }
<<<<<<< HEAD
=======

    /**
     * Return a string representation of this Matcher
     *
     * @return string
     */
    public function __toString()
    {
        return '<MustBe>';
    }
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
}
