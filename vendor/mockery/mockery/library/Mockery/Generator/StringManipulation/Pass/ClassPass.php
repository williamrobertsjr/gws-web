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

<<<<<<< HEAD
use Mockery;
use Mockery\Generator\MockConfiguration;
use function class_exists;
use function ltrim;
use function str_replace;

class ClassPass implements Pass
{
    /**
     * @param  string $code
     * @return string
     */
=======
use Mockery\Generator\MockConfiguration;

class ClassPass implements Pass
{
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function apply($code, MockConfiguration $config)
    {
        $target = $config->getTargetClass();

<<<<<<< HEAD
        if (! $target) {
=======
        if (!$target) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            return $code;
        }

        if ($target->isFinal()) {
            return $code;
        }

<<<<<<< HEAD
        $className = ltrim($target->getName(), '\\');

        if (! class_exists($className)) {
            Mockery::declareClass($className);
        }

        return str_replace(
            'implements MockInterface',
            'extends \\' . $className . ' implements MockInterface',
            $code
        );
=======
        $className = ltrim($target->getName(), "\\");

        if (!class_exists($className)) {
            \Mockery::declareClass($className);
        }

        $code = str_replace(
            "implements MockInterface",
            "extends \\" . $className . " implements MockInterface",
            $code
        );

        return $code;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }
}
