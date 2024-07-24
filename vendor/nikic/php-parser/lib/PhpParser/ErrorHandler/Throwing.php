<?php declare(strict_types=1);

namespace PhpParser\ErrorHandler;

use PhpParser\Error;
use PhpParser\ErrorHandler;

/**
 * Error handler that handles all errors by throwing them.
 *
 * This is the default strategy used by all components.
 */
<<<<<<< HEAD
class Throwing implements ErrorHandler {
    public function handleError(Error $error): void {
=======
class Throwing implements ErrorHandler
{
    public function handleError(Error $error) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        throw $error;
    }
}
