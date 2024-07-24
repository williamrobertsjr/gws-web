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

namespace Mockery\Loader;

use Mockery\Generator\MockDefinition;
<<<<<<< HEAD

use function class_exists;

class EvalLoader implements Loader
{
    /**
     * Load the given mock definition
     *
     * @return void
     */
=======
use Mockery\Loader\Loader;

class EvalLoader implements Loader
{
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function load(MockDefinition $definition)
    {
        if (class_exists($definition->getClassName(), false)) {
            return;
        }

<<<<<<< HEAD
        eval('?>' . $definition->getCode());
=======
        eval("?>" . $definition->getCode());
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }
}
