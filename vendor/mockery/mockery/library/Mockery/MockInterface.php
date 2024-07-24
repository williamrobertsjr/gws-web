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

namespace Mockery;

<<<<<<< HEAD
interface MockInterface extends LegacyMockInterface
{
    /**
     * @param mixed $something String method name or map of method => return
     *
     * @return Expectation|ExpectationInterface|HigherOrderMessage|self
=======
use Mockery\LegacyMockInterface;

interface MockInterface extends LegacyMockInterface
{
    /**
     * @param mixed $something  String method name or map of method => return
     * @return self|\Mockery\ExpectationInterface|\Mockery\Expectation|\Mockery\HigherOrderMessage
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function allows($something = []);

    /**
<<<<<<< HEAD
     * @param mixed $something String method name (optional)
     *
     * @return Expectation|ExpectationInterface|ExpectsHigherOrderMessage
=======
     * @param mixed $something  String method name (optional)
     * @return \Mockery\ExpectationInterface|\Mockery\Expectation|\Mockery\ExpectsHigherOrderMessage
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function expects($something = null);
}
