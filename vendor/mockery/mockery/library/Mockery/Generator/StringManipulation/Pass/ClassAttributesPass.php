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
use function implode;
use function str_replace;

class ClassAttributesPass implements Pass
{
    /**
     * @param  string $code
     * @return string
     */
    public function apply($code, MockConfiguration $config)
    {
        $class = $config->getTargetClass();

        if (! $class) {
=======

class ClassAttributesPass implements Pass
{
    public function apply($code, MockConfiguration $config)
    {
        $class =  $config->getTargetClass();

        if (!$class) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            return $code;
        }

        /** @var array<string> $attributes */
        $attributes = $class->getAttributes();

        if ($attributes !== []) {
<<<<<<< HEAD
            return str_replace('#[\AllowDynamicProperties]', '#[' . implode(',', $attributes) . ']', $code);
=======
            return str_replace(
                '#[\AllowDynamicProperties]',
                '#[' . implode(',', $attributes) . ']',
                $code
            );
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        }

        return $code;
    }
}
