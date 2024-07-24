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

namespace Mockery\Adapter\Phpunit;

use Mockery;
<<<<<<< HEAD
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Before;

use function method_exists;
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

/**
 * Integrates Mockery into PHPUnit. Ensures Mockery expectations are verified
 * for each test and are included by the assertion counter.
 */
trait MockeryPHPUnitIntegration
{
    use MockeryPHPUnitIntegrationAssertPostConditions;

    protected $mockeryOpen;

<<<<<<< HEAD
=======
    /**
     * Performs assertions shared by all tests of a test case. This method is
     * called before execution of a test ends and before the tearDown method.
     */
    protected function mockeryAssertPostConditions()
    {
        $this->addMockeryExpectationsToAssertionCount();
        $this->checkMockeryExceptions();
        $this->closeMockery();

        parent::assertPostConditions();
    }

>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    protected function addMockeryExpectationsToAssertionCount()
    {
        $this->addToAssertionCount(Mockery::getContainer()->mockery_getExpectationCount());
    }

    protected function checkMockeryExceptions()
    {
<<<<<<< HEAD
        if (! method_exists($this, 'markAsRisky')) {
=======
        if (!method_exists($this, "markAsRisky")) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            return;
        }

        foreach (Mockery::getContainer()->mockery_thrownExceptions() as $e) {
<<<<<<< HEAD
            if (! $e->dismissed()) {
=======
            if (!$e->dismissed()) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                $this->markAsRisky();
            }
        }
    }

    protected function closeMockery()
    {
        Mockery::close();
        $this->mockeryOpen = false;
    }

    /**
<<<<<<< HEAD
     * Performs assertions shared by all tests of a test case. This method is
     * called before execution of a test ends and before the tearDown method.
     */
    protected function mockeryAssertPostConditions()
    {
        $this->addMockeryExpectationsToAssertionCount();
        $this->checkMockeryExceptions();
        $this->closeMockery();

        parent::assertPostConditions();
=======
     * @before
     */
    protected function startMockery()
    {
        $this->mockeryOpen = true;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * @after
     */
<<<<<<< HEAD
    #[After]
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    protected function purgeMockeryContainer()
    {
        if ($this->mockeryOpen) {
            // post conditions wasn't called, so test probably failed
            Mockery::close();
        }
    }
<<<<<<< HEAD

    /**
     * @before
     */
    #[Before]
    protected function startMockery()
    {
        $this->mockeryOpen = true;
    }
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
}
