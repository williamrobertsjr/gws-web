<?php declare(strict_types=1);

namespace PhpParser;

<<<<<<< HEAD
interface ErrorHandler {
=======
interface ErrorHandler
{
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    /**
     * Handle an error generated during lexing, parsing or some other operation.
     *
     * @param Error $error The error that needs to be handled
     */
<<<<<<< HEAD
    public function handleError(Error $error): void;
=======
    public function handleError(Error $error);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
}
