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
use Mockery\Generator\MockConfiguration;
use function array_map;
use function in_array;
use function preg_replace;
use function sprintf;
use function str_replace;

class AvoidMethodClashPass implements Pass
{
    /**
     * @param  string $code
     * @return string
     */
    public function apply($code, MockConfiguration $config)
    {
        $names = array_map(static function ($method) {
            return $method->getName();
        }, $config->getMethodsToMock());

        foreach (['allows', 'expects'] as $method) {
            if (in_array($method, $names, true)) {
                $code = preg_replace(sprintf('#// start method %s.*// end method %s#ms', $method, $method), '', $code);

                $code = str_replace(' implements MockInterface', ' implements LegacyMockInterface', $code);
=======
use Mockery\Generator\Method;
use Mockery\Generator\Parameter;
use Mockery\Generator\MockConfiguration;

class AvoidMethodClashPass implements Pass
{
    public function apply($code, MockConfiguration $config)
    {
        $names = array_map(function ($method) {
            return $method->getName();
        }, $config->getMethodsToMock());

        foreach (["allows", "expects"] as $method) {
            if (in_array($method, $names)) {
                $code = preg_replace(
                    "#// start method {$method}.*// end method {$method}#ms",
                    "",
                    $code
                );

                $code = str_replace(" implements MockInterface", " implements LegacyMockInterface", $code);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            }
        }

        return $code;
    }
}
