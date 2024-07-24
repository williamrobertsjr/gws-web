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
use function implode;
use function is_object;
use function method_exists;

class Ducktype extends MatcherAbstract
{
    /**
     * Return a string representation of this Matcher
     *
     * @return string
     */
    public function __toString()
    {
        return '<Ducktype[' . implode(', ', $this->_expected) . ']>';
    }

    /**
     * Check if the actual value matches the expected.
     *
     * @template TMixed
     *
     * @param TMixed $actual
     *
=======
class Ducktype extends MatcherAbstract
{
    /**
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
            return false;
        }

        foreach ($this->_expected as $method) {
            if (! method_exists($actual, $method)) {
                return false;
            }
        }

        return true;
    }
=======
        if (!is_object($actual)) {
            return false;
        }
        foreach ($this->_expected as $method) {
            if (!method_exists($actual, $method)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Return a string representation of this Matcher
     *
     * @return string
     */
    public function __toString()
    {
        return '<Ducktype[' . implode(', ', $this->_expected) . ']>';
    }
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
}
