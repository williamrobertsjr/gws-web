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
use function spl_object_hash;

=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
class Undefined
{
    /**
     * Call capturing to merely return this same object.
     *
     * @param string $method
<<<<<<< HEAD
     * @param array  $args
     *
=======
     * @param array $args
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     * @return self
     */
    public function __call($method, array $args)
    {
        return $this;
    }

    /**
<<<<<<< HEAD
     * Return a string, avoiding E_RECOVERABLE_ERROR.
=======
     * Return a string, avoiding E_RECOVERABLE_ERROR
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     *
     * @return string
     */
    public function __toString()
    {
<<<<<<< HEAD
        return self::class . ':' . spl_object_hash($this);
=======
        return __CLASS__ . ":" . spl_object_hash($this);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }
}
