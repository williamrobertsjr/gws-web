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

class Not extends MatcherAbstract
{
    /**
<<<<<<< HEAD
     * Return a string representation of this Matcher
     *
     * @return string
     */
    public function __toString()
    {
        return '<Not>';
    }

    /**
     * Check if the actual value does not match the expected (in this
     * case it's specifically NOT expected).
     *
     * @template TMixed
     *
     * @param TMixed $actual
     *
     * @return bool
     */
    public function match(&$actual)
    {
        return $actual !== $this->_expected;
=======
     * Check if the actual value does not match the expected (in this
     * case it's specifically NOT expected).
     *
     * @param mixed $actual
     * @return bool
     */
    public function match(&$actual)
    {
        return $actual !== $this->_expected;
    }

    /**
     * Return a string representation of this Matcher
     *
     * @return string
     */
    public function __toString()
    {
        return '<Not>';
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }
}
