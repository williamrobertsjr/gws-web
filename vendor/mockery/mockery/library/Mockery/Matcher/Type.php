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
use function class_exists;
use function function_exists;
use function interface_exists;
use function is_string;
use function strtolower;
use function ucfirst;

class Type extends MatcherAbstract
{
    /**
     * Return a string representation of this Matcher
     *
     * @return string
     */
    public function __toString()
    {
        return '<' . ucfirst($this->_expected) . '>';
    }

    /**
     * Check if the actual value matches the expected.
     *
     * @template TMixed
     *
     * @param TMixed $actual
     *
=======
class Type extends MatcherAbstract
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
        $function = $this->_expected === 'real' ? 'is_float' : 'is_' . strtolower($this->_expected);

        if (function_exists($function)) {
            return $function($actual);
        }

        if (! is_string($this->_expected)) {
            return false;
        }

        if (class_exists($this->_expected) || interface_exists($this->_expected)) {
            return $actual instanceof $this->_expected;
        }

        return false;
    }
=======
        if ($this->_expected == 'real') {
            $function = 'is_float';
        } else {
            $function = 'is_' . strtolower($this->_expected);
        }
        if (function_exists($function)) {
            return $function($actual);
        } elseif (is_string($this->_expected)
        && (class_exists($this->_expected) || interface_exists($this->_expected))) {
            return $actual instanceof $this->_expected;
        }
        return false;
    }

    /**
     * Return a string representation of this Matcher
     *
     * @return string
     */
    public function __toString()
    {
        return '<' . ucfirst($this->_expected) . '>';
    }
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
}
