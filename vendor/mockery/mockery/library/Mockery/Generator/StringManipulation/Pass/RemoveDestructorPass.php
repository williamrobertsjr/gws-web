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
use function preg_replace;
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

/**
 * Remove mock's empty destructor if we tend to use original class destructor
 */
<<<<<<< HEAD
class RemoveDestructorPass implements Pass
{
    /**
     * @param  string $code
     * @return string
     */
=======
class RemoveDestructorPass
{
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function apply($code, MockConfiguration $config)
    {
        $target = $config->getTargetClass();

<<<<<<< HEAD
        if (! $target) {
            return $code;
        }

        if (! $config->isMockOriginalDestructor()) {
            return preg_replace('/public function __destruct\(\)\s+\{.*?\}/sm', '', $code);
=======
        if (!$target) {
            return $code;
        }

        if (!$config->isMockOriginalDestructor()) {
            $code = preg_replace('/public function __destruct\(\)\s+\{.*?\}/sm', '', $code);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        }

        return $code;
    }
}
