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
use Mockery\Expectation;

abstract class CountValidatorAbstract implements CountValidatorInterface
=======
abstract class CountValidatorAbstract
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
{
    /**
     * Expectation for which this validator is assigned
     *
<<<<<<< HEAD
     * @var Expectation
=======
     * @var \Mockery\Expectation
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    protected $_expectation = null;

    /**
     * Call count limit
     *
     * @var int
     */
    protected $_limit = null;

    /**
     * Set Expectation object and upper call limit
     *
<<<<<<< HEAD
     * @param int $limit
     */
    public function __construct(Expectation $expectation, $limit)
=======
     * @param \Mockery\Expectation $expectation
     * @param int $limit
     */
    public function __construct(\Mockery\Expectation $expectation, $limit)
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    {
        $this->_expectation = $expectation;
        $this->_limit = $limit;
    }

    /**
     * Checks if the validator can accept an additional nth call
     *
     * @param int $n
<<<<<<< HEAD
     *
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     * @return bool
     */
    public function isEligible($n)
    {
<<<<<<< HEAD
        return $n < $this->_limit;
=======
        return ($n < $this->_limit);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * Validate the call count against this validator
     *
     * @param int $n
<<<<<<< HEAD
     *
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     * @return bool
     */
    abstract public function validate($n);
}
