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

/**
 * @deprecated Implement \Mockery\Matcher\MatcherInterface instead of extending this class
 * @see https://github.com/mockery/mockery/pull/1338
 */
abstract class MatcherAbstract implements MatcherInterface
{
    /**
     * The expected value (or part thereof)
     *
<<<<<<< HEAD
     * @template TExpected
     *
     * @var TExpected
=======
     * @var mixed
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    protected $_expected = null;

    /**
     * Set the expected value
     *
<<<<<<< HEAD
     * @template TExpected
     *
     * @param TExpected $expected
=======
     * @param mixed $expected
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct($expected = null)
    {
        $this->_expected = $expected;
    }
}
