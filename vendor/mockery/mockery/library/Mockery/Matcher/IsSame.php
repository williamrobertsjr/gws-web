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

class IsSame extends MatcherAbstract
{
    /**
<<<<<<< HEAD
     * Return a string representation of this Matcher
     *
     * @return string
     */
    public function __toString()
    {
        return '<IsSame>';
    }

    /**
     * Check if the actual value matches the expected.
     *
     * @template TMixed
     *
     * @param TMixed $actual
     *
     * @return bool
     */
    public function match(&$actual)
    {
        return $this->_expected === $actual;
=======
     * Check if the actual value matches the expected.
     *
     * @param mixed $actual
     * @return bool
     */
    public function match(&$actual)
    {
        return $this->_expected === $actual;
    }

    /**
     * Return a string representation of this Matcher
     *
     * @return string
     */
    public function __toString()
    {
        return '<IsSame>';
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }
}
