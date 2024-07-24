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

namespace Mockery\CountValidator;

<<<<<<< HEAD
use Mockery\Exception\InvalidCountException;

use const PHP_EOL;
=======
use Mockery;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

class AtMost extends CountValidatorAbstract
{
    /**
     * Validate the call count against this validator
     *
     * @param int $n
<<<<<<< HEAD
     *
     * @throws InvalidCountException
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     * @return bool
     */
    public function validate($n)
    {
        if ($this->_limit < $n) {
<<<<<<< HEAD
            $exception = new InvalidCountException(
=======
            $exception = new Mockery\Exception\InvalidCountException(
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                'Method ' . (string) $this->_expectation
                . ' from ' . $this->_expectation->getMock()->mockery_getName()
                . ' should be called' . PHP_EOL
                . ' at most ' . $this->_limit . ' times but called ' . $n
                . ' times.'
            );
            $exception->setMock($this->_expectation->getMock())
                ->setMethodName((string) $this->_expectation)
                ->setExpectedCountComparative('<=')
                ->setExpectedCount($this->_limit)
                ->setActualCount($n);
            throw $exception;
        }
    }
}
