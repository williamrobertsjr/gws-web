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

use ReflectionAttribute;
use ReflectionClass;
<<<<<<< HEAD
use ReflectionMethod;

use function array_map;
use function array_merge;
=======

use function array_map;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
use function array_unique;

use const PHP_VERSION_ID;

class DefinedTargetClass implements TargetClassInterface
{
<<<<<<< HEAD
    /**
     * @var class-string
     */
    private $name;

    /**
     * @var ReflectionClass
     */
    private $rfc;

    /**
     * @param ReflectionClass   $rfc
     * @param class-string|null $alias
     */
    public function __construct(ReflectionClass $rfc, $alias = null)
    {
        $this->rfc = $rfc;
        $this->name = $alias ?? $rfc->getName();
    }

    /**
     * @return class-string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @param  class-string      $name
     * @param  class-string|null $alias
     * @return self
     */
=======
    private $rfc;
    private $name;

    public function __construct(ReflectionClass $rfc, $alias = null)
    {
        $this->rfc = $rfc;
        $this->name = $alias === null ? $rfc->getName() : $alias;
    }

>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public static function factory($name, $alias = null)
    {
        return new self(new ReflectionClass($name), $alias);
    }

<<<<<<< HEAD
    /**
     * @return list<class-string>
     */
    public function getAttributes()
    {
        if (PHP_VERSION_ID < 80000) {
=======
    public function getAttributes()
    {
        if (\PHP_VERSION_ID < 80000) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            return [];
        }

        return array_unique(
            array_merge(
                ['\AllowDynamicProperties'],
                array_map(
                    static function (ReflectionAttribute $attribute): string {
                        return '\\' . $attribute->getName();
                    },
                    $this->rfc->getAttributes()
                )
            )
        );
    }

<<<<<<< HEAD
    /**
     * @return array<class-string,self>
     */
    public function getInterfaces()
    {
        return array_map(
            static function (ReflectionClass $interface): self {
                return new self($interface);
            },
            $this->rfc->getInterfaces()
        );
    }

    /**
     * @return list<Method>
     */
    public function getMethods()
    {
        return array_map(
            static function (ReflectionMethod $method): Method {
                return new Method($method);
            },
            $this->rfc->getMethods()
        );
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
=======
    public function getName()
    {
        return $this->name;
    }

    public function isAbstract()
    {
        return $this->rfc->isAbstract();
    }

    public function isFinal()
    {
        return $this->rfc->isFinal();
    }

    public function getMethods()
    {
        return array_map(function ($method) {
            return new Method($method);
        }, $this->rfc->getMethods());
    }

    public function getInterfaces()
    {
        $class = __CLASS__;
        return array_map(function ($interface) use ($class) {
            return new $class($interface);
        }, $this->rfc->getInterfaces());
    }

    public function __toString()
    {
        return $this->getName();
    }

>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function getNamespaceName()
    {
        return $this->rfc->getNamespaceName();
    }

<<<<<<< HEAD
    /**
     * @return string
     */
=======
    public function inNamespace()
    {
        return $this->rfc->inNamespace();
    }

>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function getShortName()
    {
        return $this->rfc->getShortName();
    }

<<<<<<< HEAD
    /**
     * @return bool
     */
=======
    public function implementsInterface($interface)
    {
        return $this->rfc->implementsInterface($interface);
    }

>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function hasInternalAncestor()
    {
        if ($this->rfc->isInternal()) {
            return true;
        }

        $child = $this->rfc;
        while ($parent = $child->getParentClass()) {
            if ($parent->isInternal()) {
                return true;
            }
<<<<<<< HEAD

=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            $child = $parent;
        }

        return false;
    }
<<<<<<< HEAD

    /**
     * @param  class-string $interface
     * @return bool
     */
    public function implementsInterface($interface)
    {
        return $this->rfc->implementsInterface($interface);
    }

    /**
     * @return bool
     */
    public function inNamespace()
    {
        return $this->rfc->inNamespace();
    }

    /**
     * @return bool
     */
    public function isAbstract()
    {
        return $this->rfc->isAbstract();
    }

    /**
     * @return bool
     */
    public function isFinal()
    {
        return $this->rfc->isFinal();
    }
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
}
