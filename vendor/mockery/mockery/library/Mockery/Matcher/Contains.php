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
use function array_values;
use function implode;

class Contains extends MatcherAbstract
{
    /**
     * Return a string representation of this Matcher
     *
     * @return string
     */
    public function __toString()
    {
        $elements = [];
        foreach ($this->_expected as $v) {
            $elements[] = (string) $v;
        }

        return '<Contains[' . implode(', ', $elements) . ']>';
    }

    /**
     * Check if the actual value matches the expected.
     *
     * @template TMixed
     *
     * @param TMixed $actual
     *
=======
class Contains extends MatcherAbstract
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
        $values = array_values($actual);
        foreach ($this->_expected as $exp) {
            $match = false;
            foreach ($values as $val) {
                if ($exp === $val || $exp == $val) {
                    $match = true;
                    break;
                }
            }
<<<<<<< HEAD

=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            if ($match === false) {
                return false;
            }
        }
<<<<<<< HEAD

        return true;
    }
=======
        return true;
    }

    /**
     * Return a string representation of this Matcher
     *
     * @return string
     */
    public function __toString()
    {
        $return = '<Contains[';
        $elements = array();
        foreach ($this->_expected as $v) {
            $elements[] = (string) $v;
        }
        $return .= implode(', ', $elements) . ']>';
        return $return;
    }
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
}
