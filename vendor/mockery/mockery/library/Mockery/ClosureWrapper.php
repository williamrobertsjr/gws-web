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

<<<<<<< HEAD
use Closure;

use function func_get_args;
=======
use Mockery\Matcher\Closure;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

/**
 * @internal
 */
class ClosureWrapper
{
    private $closure;

<<<<<<< HEAD
    public function __construct(Closure $closure)
=======
    public function __construct(\Closure $closure)
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    {
        $this->closure = $closure;
    }

<<<<<<< HEAD
    /**
     * @return mixed
     */
    public function __invoke()
    {
        return ($this->closure)(...func_get_args());
=======
    public function __invoke()
    {
        return call_user_func_array($this->closure, func_get_args());
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }
}
