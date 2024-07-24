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

interface ExpectationInterface
{
    /**
<<<<<<< HEAD
     * @template TArgs
     *
     * @param TArgs ...$args
     *
     * @return self
     */
    public function andReturn(...$args);

    /**
     * @return self
     */
    public function andReturns();

    /**
     * @return LegacyMockInterface|MockInterface
     */
    public function getMock();

    /**
     * @return int
     */
    public function getOrderNumber();
=======
     * @return int
     */
    public function getOrderNumber();

    /**
     * @return LegacyMockInterface|MockInterface
     */
    public function getMock();

    /**
     * @param mixed $args
     * @return self
     */
    public function andReturn(...$args);

    /**
     * @return self
     */
    public function andReturns();
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
}
