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

namespace Mockery\Matcher;

class AnyArgs extends MatcherAbstract implements ArgumentListMatcher
{
<<<<<<< HEAD
    public function __toString()
    {
        return '<Any Arguments>';
    }

    /**
     * @template TMixed
     *
     * @param TMixed $actual
     *
     * @return bool
     */
    public function match(&$actual)
    {
        return true;
=======
    /**
     * @inheritdoc
     */
    public function match(&$actual)
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return '<Any Arguments>';
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }
}
