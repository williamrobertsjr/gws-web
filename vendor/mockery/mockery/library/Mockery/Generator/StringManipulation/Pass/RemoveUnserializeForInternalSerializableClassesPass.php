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

namespace Mockery\Generator\StringManipulation\Pass;

use Mockery\Generator\MockConfiguration;
<<<<<<< HEAD
use function strrpos;
use function substr;
use const PHP_VERSION_ID;
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

/**
 * Internal classes can not be instantiated with the newInstanceWithoutArgs
 * reflection method, so need the serialization hack. If the class also
 * implements Serializable, we need to replace the standard unserialize method
 * definition with a dummy
 */
<<<<<<< HEAD
class RemoveUnserializeForInternalSerializableClassesPass implements Pass
{
    public const DUMMY_METHOD_DEFINITION = 'public function unserialize(string $data): void {} ';

    public const DUMMY_METHOD_DEFINITION_LEGACY = 'public function unserialize($string) {} ';

    /**
     * @param  string $code
     * @return string
     */
=======
class RemoveUnserializeForInternalSerializableClassesPass
{
    const DUMMY_METHOD_DEFINITION_LEGACY = 'public function unserialize($string) {} ';
    const DUMMY_METHOD_DEFINITION = 'public function unserialize(string $data): void {} ';

>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function apply($code, MockConfiguration $config)
    {
        $target = $config->getTargetClass();

<<<<<<< HEAD
        if (! $target) {
            return $code;
        }

        if (! $target->hasInternalAncestor() || ! $target->implementsInterface('Serializable')) {
            return $code;
        }

        return $this->appendToClass(
            $code,
            PHP_VERSION_ID < 80100 ? self::DUMMY_METHOD_DEFINITION_LEGACY : self::DUMMY_METHOD_DEFINITION
        );
=======
        if (!$target) {
            return $code;
        }

        if (!$target->hasInternalAncestor() || !$target->implementsInterface("Serializable")) {
            return $code;
        }

        $code = $this->appendToClass($code, \PHP_VERSION_ID < 80100 ? self::DUMMY_METHOD_DEFINITION_LEGACY : self::DUMMY_METHOD_DEFINITION);

        return $code;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    protected function appendToClass($class, $code)
    {
<<<<<<< HEAD
        $lastBrace = strrpos($class, '}');
        return substr($class, 0, $lastBrace) . $code . "\n    }\n";
=======
        $lastBrace = strrpos($class, "}");
        $class = substr($class, 0, $lastBrace) . $code . "\n    }\n";
        return $class;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }
}
