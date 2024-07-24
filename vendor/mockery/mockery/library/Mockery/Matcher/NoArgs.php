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

<<<<<<< HEAD
use function count;

class NoArgs extends MatcherAbstract implements ArgumentListMatcher
{
    public function __toString()
    {
        return '<No Arguments>';
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
        return count($actual) === 0;
=======
class NoArgs extends MatcherAbstract implements ArgumentListMatcher
{
    /**
     * @inheritdoc
     */
    public function match(&$actual)
    {
        return count($actual) == 0;
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return '<No Arguments>';
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }
}
