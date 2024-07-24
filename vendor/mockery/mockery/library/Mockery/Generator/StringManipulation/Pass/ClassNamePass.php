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
use function ltrim;
use function str_replace;

class ClassNamePass implements Pass
{
    /**
     * @param  string $code
     * @return string
     */
=======

class ClassNamePass implements Pass
{
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function apply($code, MockConfiguration $config)
    {
        $namespace = $config->getNamespaceName();

<<<<<<< HEAD
        $namespace = ltrim($namespace, '\\');

        $className = $config->getShortName();

        $code = str_replace('namespace Mockery;', $namespace !== '' ? 'namespace ' . $namespace . ';' : '', $code);

        return str_replace('class Mock', 'class ' . $className, $code);
=======
        $namespace = ltrim($namespace, "\\");

        $className = $config->getShortName();

        $code = str_replace(
            'namespace Mockery;',
            $namespace ? 'namespace ' . $namespace . ';' : '',
            $code
        );

        $code = str_replace(
            'class Mock',
            'class ' . $className,
            $code
        );

        return $code;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }
}
