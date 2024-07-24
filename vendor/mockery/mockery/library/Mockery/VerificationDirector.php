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

class VerificationDirector
{
<<<<<<< HEAD
    /**
     * @var VerificationExpectation
     */
    private $expectation;

    /**
     * @var ReceivedMethodCalls
     */
    private $receivedMethodCalls;
=======
    private $receivedMethodCalls;
    private $expectation;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    public function __construct(ReceivedMethodCalls $receivedMethodCalls, VerificationExpectation $expectation)
    {
        $this->receivedMethodCalls = $receivedMethodCalls;
        $this->expectation = $expectation;
    }

<<<<<<< HEAD
    /**
     * @return self
     */
    public function atLeast()
    {
        return $this->cloneWithoutCountValidatorsApplyAndVerify('atLeast', []);
    }

    /**
     * @return self
     */
    public function atMost()
    {
        return $this->cloneWithoutCountValidatorsApplyAndVerify('atMost', []);
    }

    /**
     * @param int $minimum
     * @param int $maximum
     *
     * @return self
     */
    public function between($minimum, $maximum)
    {
        return $this->cloneWithoutCountValidatorsApplyAndVerify('between', [$minimum, $maximum]);
    }

    /**
     * @return self
     */
    public function once()
    {
        return $this->cloneWithoutCountValidatorsApplyAndVerify('once', []);
    }

    /**
     * @param int $limit
     *
     * @return self
     */
    public function times($limit = null)
    {
        return $this->cloneWithoutCountValidatorsApplyAndVerify('times', [$limit]);
    }

    /**
     * @return self
     */
    public function twice()
    {
        return $this->cloneWithoutCountValidatorsApplyAndVerify('twice', []);
    }

    public function verify()
    {
        $this->receivedMethodCalls->verify($this->expectation);
    }

    /**
     * @template TArgs
     *
     * @param TArgs $args
     *
     * @return self
     */
    public function with(...$args)
    {
        return $this->cloneApplyAndVerify('with', $args);
    }

    /**
     * @return self
     */
    public function withAnyArgs()
    {
        return $this->cloneApplyAndVerify('withAnyArgs', []);
    }

    /**
     * @template TArgs
     *
     * @param TArgs $args
     *
     * @return self
     */
    public function withArgs($args)
    {
        return $this->cloneApplyAndVerify('withArgs', [$args]);
    }

    /**
     * @return self
     */
    public function withNoArgs()
    {
        return $this->cloneApplyAndVerify('withNoArgs', []);
    }

    /**
     * @param string $method
     * @param array  $args
     *
     * @return self
     */
    protected function cloneApplyAndVerify($method, $args)
    {
        $verificationExpectation = clone $this->expectation;

        $verificationExpectation->{$method}(...$args);

        $verificationDirector = new self($this->receivedMethodCalls, $verificationExpectation);

        $verificationDirector->verify();

        return $verificationDirector;
    }

    /**
     * @param string $method
     * @param array  $args
     *
     * @return self
     */
    protected function cloneWithoutCountValidatorsApplyAndVerify($method, $args)
    {
        $verificationExpectation = clone $this->expectation;

        $verificationExpectation->clearCountValidators();

        $verificationExpectation->{$method}(...$args);

        $verificationDirector = new self($this->receivedMethodCalls, $verificationExpectation);

        $verificationDirector->verify();

        return $verificationDirector;
=======
    public function verify()
    {
        return $this->receivedMethodCalls->verify($this->expectation);
    }

    public function with(...$args)
    {
        return $this->cloneApplyAndVerify("with", $args);
    }

    public function withArgs($args)
    {
        return $this->cloneApplyAndVerify("withArgs", array($args));
    }

    public function withNoArgs()
    {
        return $this->cloneApplyAndVerify("withNoArgs", array());
    }

    public function withAnyArgs()
    {
        return $this->cloneApplyAndVerify("withAnyArgs", array());
    }

    public function times($limit = null)
    {
        return $this->cloneWithoutCountValidatorsApplyAndVerify("times", array($limit));
    }

    public function once()
    {
        return $this->cloneWithoutCountValidatorsApplyAndVerify("once", array());
    }

    public function twice()
    {
        return $this->cloneWithoutCountValidatorsApplyAndVerify("twice", array());
    }

    public function atLeast()
    {
        return $this->cloneWithoutCountValidatorsApplyAndVerify("atLeast", array());
    }

    public function atMost()
    {
        return $this->cloneWithoutCountValidatorsApplyAndVerify("atMost", array());
    }

    public function between($minimum, $maximum)
    {
        return $this->cloneWithoutCountValidatorsApplyAndVerify("between", array($minimum, $maximum));
    }

    protected function cloneWithoutCountValidatorsApplyAndVerify($method, $args)
    {
        $expectation = clone $this->expectation;
        $expectation->clearCountValidators();
        call_user_func_array(array($expectation, $method), $args);
        $director = new VerificationDirector($this->receivedMethodCalls, $expectation);
        $director->verify();
        return $director;
    }

    protected function cloneApplyAndVerify($method, $args)
    {
        $expectation = clone $this->expectation;
        call_user_func_array(array($expectation, $method), $args);
        $director = new VerificationDirector($this->receivedMethodCalls, $expectation);
        $director->verify();
        return $director;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }
}
