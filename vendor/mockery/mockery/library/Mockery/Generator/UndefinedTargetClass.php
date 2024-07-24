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

<<<<<<< HEAD
use function array_pop;
use function explode;
use function implode;
use function ltrim;

class UndefinedTargetClass implements TargetClassInterface
{
    /**
     * @var class-string
     */
    private $name;

    /**
     * @param class-string $name
     */
=======
use const PHP_VERSION_ID;

class UndefinedTargetClass implements TargetClassInterface
{
    private $name;

>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function __construct($name)
    {
        $this->name = $name;
    }

<<<<<<< HEAD
    /**
     * @return class-string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @param  class-string $name
     * @return self
     */
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public static function factory($name)
    {
        return new self($name);
    }

<<<<<<< HEAD
    /**
     * @return list<class-string>
     */
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function getAttributes()
    {
        return [];
    }

<<<<<<< HEAD
    /**
     * @return list<self>
     */
    public function getInterfaces()
    {
        return [];
    }

    /**
     * @return list<Method>
     */
    public function getMethods()
    {
        return [];
    }

    /**
     * @return class-string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getNamespaceName()
    {
        $parts = explode('\\', ltrim($this->getName(), '\\'));
        array_pop($parts);
        return implode('\\', $parts);
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        $parts = explode('\\', $this->getName());
        return array_pop($parts);
    }

    /**
     * @return bool
     */
    public function hasInternalAncestor()
    {
        return false;
    }

    /**
     * @param  class-string $interface
     * @return bool
     */
=======
    public function getName()
    {
        return $this->name;
    }

    public function isAbstract()
    {
        return false;
    }

    public function isFinal()
    {
        return false;
    }

    public function getMethods()
    {
        return array();
    }

    public function getInterfaces()
    {
        return array();
    }

    public function getNamespaceName()
    {
        $parts = explode("\\", ltrim($this->getName(), "\\"));
        array_pop($parts);
        return implode("\\", $parts);
    }

    public function inNamespace()
    {
        return $this->getNamespaceName() !== '';
    }

    public function getShortName()
    {
        $parts = explode("\\", $this->getName());
        return array_pop($parts);
    }

>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function implementsInterface($interface)
    {
        return false;
    }

<<<<<<< HEAD
    /**
     * @return bool
     */
    public function inNamespace()
    {
        return $this->getNamespaceName() !== '';
    }

    /**
     * @return bool
     */
    public function isAbstract()
=======
    public function hasInternalAncestor()
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    {
        return false;
    }

<<<<<<< HEAD
    /**
     * @return bool
     */
    public function isFinal()
    {
        return false;
=======
    public function __toString()
    {
        return $this->name;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }
}
