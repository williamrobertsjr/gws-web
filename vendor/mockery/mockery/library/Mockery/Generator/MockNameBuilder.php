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

namespace Mockery\Generator;

<<<<<<< HEAD
use function implode;
use function str_replace;

class MockNameBuilder
{
    /**
     * @var int
     */
    protected static $mockCounter = 0;

    /**
     * @var list<string>
     */
    protected $parts = [];

    /**
     * @param string $part
     */
=======
class MockNameBuilder
{
    protected static $mockCounter = 0;

    protected $parts = [];

>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function addPart($part)
    {
        $this->parts[] = $part;

        return $this;
    }

<<<<<<< HEAD
    /**
     * @return string
     */
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function build()
    {
        $parts = ['Mockery', static::$mockCounter++];

        foreach ($this->parts as $part) {
<<<<<<< HEAD
            $parts[] = str_replace('\\', '_', $part);
=======
            $parts[] = str_replace("\\", "_", $part);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        }

        return implode('_', $parts);
    }
}
