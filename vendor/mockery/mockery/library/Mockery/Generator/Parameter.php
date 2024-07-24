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
use ReflectionClass;
use ReflectionParameter;
use function class_exists;

/**
 * @mixin ReflectionParameter
 */
class Parameter
{
    /**
     * @var int
     */
    private static $parameterCounter = 0;

    /**
     * @var ReflectionParameter
     */
    private $rfp;

    public function __construct(ReflectionParameter $rfp)
=======

class Parameter
{
    /** @var int */
    private static $parameterCounter = 0;

    /** @var \ReflectionParameter */
    private $rfp;

    public function __construct(\ReflectionParameter $rfp)
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    {
        $this->rfp = $rfp;
    }

<<<<<<< HEAD
    /**
     * Proxy all method calls to the reflection parameter.
     *
     * @template TMixed
     * @template TResult
     *
     * @param string        $method
     * @param array<TMixed> $args
     *
     * @return TResult
     */
    public function __call($method, array $args)
    {
        /** @var TResult */
        return $this->rfp->{$method}(...$args);
=======
    public function __call($method, array $args)
    {
        return call_user_func_array(array($this->rfp, $method), $args);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * Get the reflection class for the parameter type, if it exists.
     *
     * This will be null if there was no type, or it was a scalar or a union.
     *
<<<<<<< HEAD
     * @return null|ReflectionClass
=======
     * @return \ReflectionClass|null
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     *
     * @deprecated since 1.3.3 and will be removed in 2.0.
     */
    public function getClass()
    {
        $typeHint = Reflector::getTypeHint($this->rfp, true);

<<<<<<< HEAD
        return class_exists($typeHint) ? DefinedTargetClass::factory($typeHint, false) : null;
    }

    /**
     * Get the name of the parameter.
     *
     * Some internal classes have funny looking definitions!
     *
     * @return string
     */
    public function getName()
    {
        $name = $this->rfp->getName();

        if (! $name || $name === '...') {
            return 'arg' . self::$parameterCounter++;
        }

        return $name;
=======
        return \class_exists($typeHint) ? DefinedTargetClass::factory($typeHint, false) : null;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * Get the string representation for the paramater type.
     *
<<<<<<< HEAD
     * @return null|string
=======
     * @return string|null
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function getTypeHint()
    {
        return Reflector::getTypeHint($this->rfp);
    }

    /**
     * Get the string representation for the paramater type.
     *
     * @return string
     *
     * @deprecated since 1.3.2 and will be removed in 2.0. Use getTypeHint() instead.
     */
    public function getTypeHintAsString()
    {
        return (string) Reflector::getTypeHint($this->rfp, true);
    }

    /**
<<<<<<< HEAD
=======
     * Get the name of the parameter.
     *
     * Some internal classes have funny looking definitions!
     *
     * @return string
     */
    public function getName()
    {
        $name = $this->rfp->getName();
        if (!$name || $name == '...') {
            $name = 'arg' . self::$parameterCounter++;
        }

        return $name;
    }

    /**
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     * Determine if the parameter is an array.
     *
     * @return bool
     */
    public function isArray()
    {
        return Reflector::isArray($this->rfp);
    }

    /**
     * Determine if the parameter is variadic.
     *
     * @return bool
     */
    public function isVariadic()
    {
        return $this->rfp->isVariadic();
    }
}
