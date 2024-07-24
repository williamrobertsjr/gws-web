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

interface TargetClassInterface
{
    /**
<<<<<<< HEAD
     * Returns a new instance of the current TargetClassInterface's implementation.
     *
     * @param class-string $name
     *
=======
     * Returns a new instance of the current
     * TargetClassInterface's
     * implementation.
     *
     * @param string $name
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     * @return TargetClassInterface
     */
    public static function factory($name);

<<<<<<< HEAD
    /**
     * Returns the targetClass's attributes.
     *
     * @return array<class-string>
=======

    /**
     * Returns the targetClass's attributes.
     *
     * @return array
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function getAttributes();

    /**
<<<<<<< HEAD
     * Returns the targetClass's interfaces.
     *
     * @return array<TargetClassInterface>
     */
    public function getInterfaces();
=======
     * Returns the targetClass's name.
     *
     * @return string
     */
    public function getName();
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Returns the targetClass's methods.
     *
<<<<<<< HEAD
     * @return array<Method>
=======
     * @return array
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function getMethods();

    /**
<<<<<<< HEAD
     * Returns the targetClass's name.
     *
     * @return class-string
     */
    public function getName();
=======
     * Returns the targetClass's interfaces.
     *
     * @return array
     */
    public function getInterfaces();
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Returns the targetClass's namespace name.
     *
     * @return string
     */
    public function getNamespaceName();

    /**
     * Returns the targetClass's short name.
     *
     * @return string
     */
    public function getShortName();

    /**
<<<<<<< HEAD
     * Returns whether the targetClass has
     * an internal ancestor.
     *
     * @return bool
     */
    public function hasInternalAncestor();

    /**
     * Returns whether the targetClass is in
     * the passed interface.
     *
     * @param class-string|string $interface
     *
     * @return bool
     */
    public function implementsInterface($interface);
=======
     * Returns whether the targetClass is abstract.
     *
     * @return boolean
     */
    public function isAbstract();

    /**
     * Returns whether the targetClass is final.
     *
     * @return boolean
     */
    public function isFinal();
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Returns whether the targetClass is in namespace.
     *
<<<<<<< HEAD
     * @return bool
=======
     * @return boolean
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function inNamespace();

    /**
<<<<<<< HEAD
     * Returns whether the targetClass is abstract.
     *
     * @return bool
     */
    public function isAbstract();

    /**
     * Returns whether the targetClass is final.
     *
     * @return bool
     */
    public function isFinal();
=======
     * Returns whether the targetClass is in
     * the passed interface.
     *
     * @param mixed $interface
     * @return boolean
     */
    public function implementsInterface($interface);

    /**
     * Returns whether the targetClass has
     * an internal ancestor.
     *
     * @return boolean
     */
    public function hasInternalAncestor();
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
}
