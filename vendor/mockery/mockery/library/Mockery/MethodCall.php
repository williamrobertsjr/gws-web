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

class MethodCall
{
<<<<<<< HEAD
    /**
     * @var array
     */
    private $args;

    /**
     * @var string
     */
    private $method;

    /**
     * @param string $method
     * @param array  $args
     */
=======
    private $method;
    private $args;

>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function __construct($method, $args)
    {
        $this->method = $method;
        $this->args = $args;
    }

<<<<<<< HEAD
    /**
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
=======
    public function getMethod()
    {
        return $this->method;
    }

    public function getArgs()
    {
        return $this->args;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }
}
