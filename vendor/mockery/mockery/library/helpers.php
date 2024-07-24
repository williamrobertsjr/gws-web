<?php

/**
 * Mockery (https://docs.mockery.io/)
 *
 * @copyright https://github.com/mockery/mockery/blob/HEAD/COPYRIGHT.md
<<<<<<< HEAD
 * @license https://github.com/mockery/mockery/blob/HEAD/LICENSE BSD 3-Clause License
 * @link https://github.com/mockery/mockery for the canonical source repository
 */

use Mockery\LegacyMockInterface;
use Mockery\Matcher\AndAnyOtherArgs;
use Mockery\Matcher\AnyArgs;
use Mockery\MockInterface;

if (! \function_exists('mock')) {
    /**
     * @template TMock of object
     *
     * @param array<class-string<TMock>|TMock|Closure(LegacyMockInterface&MockInterface&TMock):LegacyMockInterface&MockInterface&TMock|array<TMock>> $args
     *
     * @return LegacyMockInterface&MockInterface&TMock
     */
=======
 * @license   https://github.com/mockery/mockery/blob/HEAD/LICENSE BSD 3-Clause License
 * @link      https://github.com/mockery/mockery for the canonical source repository
 */

use Mockery\Matcher\AndAnyOtherArgs;
use Mockery\Matcher\AnyArgs;

if (!function_exists("mock")) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    function mock(...$args)
    {
        return Mockery::mock(...$args);
    }
}

<<<<<<< HEAD
if (! \function_exists('spy')) {
    /**
     * @template TSpy of object
     *
     * @param array<class-string<TSpy>|TSpy|Closure(LegacyMockInterface&MockInterface&TSpy):LegacyMockInterface&MockInterface&TSpy|array<TSpy>> $args
     *
     * @return LegacyMockInterface&MockInterface&TSpy
     */
=======
if (!function_exists("spy")) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    function spy(...$args)
    {
        return Mockery::spy(...$args);
    }
}

<<<<<<< HEAD
if (! \function_exists('namedMock')) {
    /**
     * @template TNamedMock of object
     *
     * @param array<class-string<TNamedMock>|TNamedMock|array<TNamedMock>> $args
     *
     * @return LegacyMockInterface&MockInterface&TNamedMock
     */
=======
if (!function_exists("namedMock")) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    function namedMock(...$args)
    {
        return Mockery::namedMock(...$args);
    }
}

<<<<<<< HEAD
if (! \function_exists('anyArgs')) {
    function anyArgs(): AnyArgs
=======
if (!function_exists("anyArgs")) {
    function anyArgs()
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    {
        return new AnyArgs();
    }
}

<<<<<<< HEAD
if (! \function_exists('andAnyOtherArgs')) {
    function andAnyOtherArgs(): AndAnyOtherArgs
=======
if (!function_exists("andAnyOtherArgs")) {
    function andAnyOtherArgs()
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    {
        return new AndAnyOtherArgs();
    }
}

<<<<<<< HEAD
if (! \function_exists('andAnyOthers')) {
    function andAnyOthers(): AndAnyOtherArgs
=======
if (!function_exists("andAnyOthers")) {
    function andAnyOthers()
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    {
        return new AndAnyOtherArgs();
    }
}
<<<<<<< HEAD
=======
//
//$currentDirectory = dirname(__FILE__,2);
//$libraryDirectory = $currentDirectory . '/library';
//if (! file_exists($libraryDirectory))
//{
//    symlink(
//        $currentDirectory . '/library',
//        $libraryDirectory
//    );
//}
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
