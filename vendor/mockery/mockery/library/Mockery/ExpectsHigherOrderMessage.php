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

class ExpectsHigherOrderMessage extends HigherOrderMessage
{
    public function __construct(MockInterface $mock)
    {
<<<<<<< HEAD
        parent::__construct($mock, 'shouldReceive');
    }

    /**
     * @param string $method
     * @param array  $args
     *
     * @return Expectation|ExpectationInterface|HigherOrderMessage
=======
        parent::__construct($mock, "shouldReceive");
    }
    /**
     * @return \Mockery\Expectation
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __call($method, $args)
    {
        $expectation = parent::__call($method, $args);

        return $expectation->once();
    }
}
