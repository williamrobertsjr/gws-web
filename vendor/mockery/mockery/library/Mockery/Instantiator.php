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

use Closure;
<<<<<<< HEAD
use Exception;
use InvalidArgumentException;
use ReflectionClass;
use UnexpectedValueException;

use function class_exists;
use function restore_error_handler;
use function set_error_handler;
use function sprintf;
use function strlen;
use function unserialize;

/**
 * This is a trimmed down version of https://github.com/doctrine/instantiator, without the caching mechanism.
=======
use ReflectionClass;
use UnexpectedValueException;
use InvalidArgumentException;

/**
 * This is a trimmed down version of https://github.com/doctrine/instantiator,
 * basically without the caching
 *
 * @author Marco Pivetta <ocramius@gmail.com>
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
 */
final class Instantiator
{
    /**
<<<<<<< HEAD
     * @template TClass of object
     *
     * @param class-string<TClass> $className
     *
     * @throws InvalidArgumentException
     * @throws UnexpectedValueException
     *
     * @return TClass
     */
    public function instantiate($className): object
    {
        return $this->buildFactory($className)();
    }

    /**
     * @throws UnexpectedValueException
     */
    private function attemptInstantiationViaUnSerialization(
        ReflectionClass $reflectionClass,
        string $serializedString
    ): void {
        set_error_handler(static function ($code, $message, $file, $line) use ($reflectionClass, &$error): void {
            $msg = sprintf(
                'Could not produce an instance of "%s" via un-serialization, since an error was triggered in file "%s" at line "%d"',
                $reflectionClass->getName(),
                $file,
                $line
            );

            $error = new UnexpectedValueException($msg, 0, new Exception($message, $code));
        });

        try {
            unserialize($serializedString);
        } catch (Exception $exception) {
            restore_error_handler();

            throw new UnexpectedValueException(
                sprintf(
                    'An exception was raised while trying to instantiate an instance of "%s" via un-serialization',
                    $reflectionClass->getName()
                ),
                0,
                $exception
            );
        }

        restore_error_handler();

        if ($error instanceof UnexpectedValueException) {
            throw $error;
        }
    }

    /**
     * Builds a {@see Closure} capable of instantiating the given $className without invoking its constructor.
     */
    private function buildFactory(string $className): Closure
=======
     * {@inheritDoc}
     */
    public function instantiate($className)
    {
        $factory    = $this->buildFactory($className);
        $instance   = $factory();

        return $instance;
    }

    /**
     * Builds a {@see \Closure} capable of instantiating the given $className without
     * invoking its constructor.
     *
     * @param string $className
     *
     * @return Closure
     */
    private function buildFactory($className)
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    {
        $reflectionClass = $this->getReflectionClass($className);

        if ($this->isInstantiableViaReflection($reflectionClass)) {
<<<<<<< HEAD
            return static function () use ($reflectionClass) {
=======
            return function () use ($reflectionClass) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                return $reflectionClass->newInstanceWithoutConstructor();
            };
        }

<<<<<<< HEAD
        $serializedString = sprintf('O:%d:"%s":0:{}', strlen($className), $className);

        $this->attemptInstantiationViaUnSerialization($reflectionClass, $serializedString);

        return static function () use ($serializedString) {
=======
        $serializedString = sprintf(
            'O:%d:"%s":0:{}',
            strlen($className),
            $className
        );

        $this->attemptInstantiationViaUnSerialization($reflectionClass, $serializedString);

        return function () use ($serializedString) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            return unserialize($serializedString);
        };
    }

    /**
<<<<<<< HEAD
     * @throws InvalidArgumentException
     */
    private function getReflectionClass(string $className): ReflectionClass
    {
        if (! class_exists($className)) {
            throw new InvalidArgumentException(sprintf('Class:%s does not exist', $className));
=======
     * @param string $className
     *
     * @return ReflectionClass
     *
     * @throws InvalidArgumentException
     */
    private function getReflectionClass($className)
    {
        if (! class_exists($className)) {
            throw new InvalidArgumentException("Class:$className does not exist");
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        }

        $reflection = new ReflectionClass($className);

        if ($reflection->isAbstract()) {
<<<<<<< HEAD
            throw new InvalidArgumentException(sprintf('Class:%s is an abstract class', $className));
=======
            throw new InvalidArgumentException("Class:$className is an abstract class");
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        }

        return $reflection;
    }

    /**
<<<<<<< HEAD
     * Verifies whether the given class is to be considered internal
     */
    private function hasInternalAncestors(ReflectionClass $reflectionClass): bool
=======
     * @param ReflectionClass $reflectionClass
     * @param string          $serializedString
     *
     * @throws UnexpectedValueException
     *
     * @return void
     */
    private function attemptInstantiationViaUnSerialization(ReflectionClass $reflectionClass, $serializedString)
    {
        set_error_handler(function ($code, $message, $file, $line) use ($reflectionClass, & $error) {
            $msg = sprintf(
                'Could not produce an instance of "%s" via un-serialization, since an error was triggered in file "%s" at line "%d"',
                $reflectionClass->getName(),
                $file,
                $line
            );

            $error = new UnexpectedValueException($msg, 0, new \Exception($message, $code));
        });

        try {
            unserialize($serializedString);
        } catch (\Exception $exception) {
            restore_error_handler();

            throw new UnexpectedValueException("An exception was raised while trying to instantiate an instance of \"{$reflectionClass->getName()}\" via un-serialization", 0, $exception);
        }

        restore_error_handler();

        if ($error) {
            throw $error;
        }
    }

    /**
     * @param ReflectionClass $reflectionClass
     *
     * @return bool
     */
    private function isInstantiableViaReflection(ReflectionClass $reflectionClass)
    {
        return ! ($reflectionClass->isInternal() && $reflectionClass->isFinal());
    }

    /**
     * Verifies whether the given class is to be considered internal
     *
     * @param ReflectionClass $reflectionClass
     *
     * @return bool
     */
    private function hasInternalAncestors(ReflectionClass $reflectionClass)
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    {
        do {
            if ($reflectionClass->isInternal()) {
                return true;
            }
        } while ($reflectionClass = $reflectionClass->getParentClass());

        return false;
    }
<<<<<<< HEAD

    /**
     * Verifies if the class is instantiable via reflection
     */
    private function isInstantiableViaReflection(ReflectionClass $reflectionClass): bool
    {
        return ! ($reflectionClass->isInternal() && $reflectionClass->isFinal());
    }
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
}
