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

namespace Mockery\Generator;

use Mockery\Reflector;
<<<<<<< HEAD
use ReflectionMethod;
use ReflectionParameter;

use function array_map;

/**
 * @mixin ReflectionMethod
 */
class Method
{
    /**
     * @var ReflectionMethod
     */
    private $method;

    public function __construct(ReflectionMethod $method)
=======

class Method
{
    /** @var \ReflectionMethod */
    private $method;

    public function __construct(\ReflectionMethod $method)
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    {
        $this->method = $method;
    }

<<<<<<< HEAD
    /**
     * @template TArgs
     * @template TMixed
     *
     * @param string       $method
     * @param array<TArgs> $args
     *
     * @return TMixed
     */
    public function __call($method, $args)
    {
        /** @var TMixed */
        return $this->method->{$method}(...$args);
    }

    /**
     * @return list<Parameter>
     */
    public function getParameters()
    {
        return array_map(static function (ReflectionParameter $parameter) {
=======
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->method, $method), $args);
    }

    /**
     * @return Parameter[]
     */
    public function getParameters()
    {
        return array_map(function (\ReflectionParameter $parameter) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            return new Parameter($parameter);
        }, $this->method->getParameters());
    }

    /**
<<<<<<< HEAD
     * @return null|string
=======
     * @return string|null
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function getReturnType()
    {
        return Reflector::getReturnType($this->method);
    }
}
