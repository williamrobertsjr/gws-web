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
use Closure;

/**
 * @method Expectation withArgs(array|Closure $args)
 */
class HigherOrderMessage
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var LegacyMockInterface|MockInterface
     */
    private $mock;
=======
/**
 * @method \Mockery\Expectation withArgs(\Closure|array $args)
 */
class HigherOrderMessage
{
    private $mock;
    private $method;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    public function __construct(MockInterface $mock, $method)
    {
        $this->mock = $mock;
        $this->method = $method;
    }

    /**
<<<<<<< HEAD
     * @param string $method
     * @param array  $args
     *
     * @return Expectation|ExpectationInterface|HigherOrderMessage
=======
     * @return \Mockery\Expectation
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __call($method, $args)
    {
        if ($this->method === 'shouldNotHaveReceived') {
            return $this->mock->{$this->method}($method, $args);
        }

        $expectation = $this->mock->{$this->method}($method);
<<<<<<< HEAD

=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $expectation->withArgs($args);
    }
}
