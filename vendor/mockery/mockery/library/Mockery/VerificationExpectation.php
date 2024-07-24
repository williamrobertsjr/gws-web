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

class VerificationExpectation extends Expectation
{
<<<<<<< HEAD
    public function __clone()
    {
        parent::__clone();

        $this->_actualCount = 0;
    }

    /**
     * @return void
     */
    public function clearCountValidators()
    {
        $this->_countValidators = [];
=======
    public function clearCountValidators()
    {
        $this->_countValidators = array();
    }

    public function __clone()
    {
        parent::__clone();
        $this->_actualCount = 0;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }
}
