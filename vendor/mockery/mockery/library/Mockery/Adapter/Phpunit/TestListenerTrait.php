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

<<<<<<< HEAD
use LogicException;
use Mockery;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\BaseTestRunner;
use PHPUnit\Util\Blacklist;
use ReflectionClass;

use function dirname;
use function method_exists;
use function sprintf;
=======
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestCase;
use PHPUnit\Util\Blacklist;
use PHPUnit\Runner\BaseTestRunner;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

class TestListenerTrait
{
    /**
     * endTest is called after each test and checks if \Mockery::close() has
     * been called, and will let the test fail if it hasn't.
     *
     * @param Test  $test
     * @param float $time
     */
    public function endTest(Test $test, $time)
    {
<<<<<<< HEAD
        if (! $test instanceof TestCase) {
=======
        if (!$test instanceof TestCase) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            // We need the getTestResultObject and getStatus methods which are
            // not part of the interface.
            return;
        }

        if ($test->getStatus() !== BaseTestRunner::STATUS_PASSED) {
            // If the test didn't pass there is no guarantee that
            // verifyMockObjects and assertPostConditions have been called.
            // And even if it did, the point here is to prevent false
            // negatives, not to make failing tests fail for more reasons.
            return;
        }

        try {
            // The self() call is used as a sentinel. Anything that throws if
            // the container is closed already will do.
<<<<<<< HEAD
            Mockery::self();
        } catch (LogicException $logicException) {
=======
            \Mockery::self();
        } catch (\LogicException $_) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            return;
        }

        $e = new ExpectationFailedException(
<<<<<<< HEAD
            sprintf(
=======
            \sprintf(
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                "Mockery's expectations have not been verified. Make sure that \Mockery::close() is called at the end of the test. Consider using %s\MockeryPHPUnitIntegration or extending %s\MockeryTestCase.",
                __NAMESPACE__,
                __NAMESPACE__
            )
        );

        /** @var \PHPUnit\Framework\TestResult $result */
        $result = $test->getTestResultObject();

        if ($result !== null) {
            $result->addFailure($test, $e, $time);
        }
    }

    public function startTestSuite()
    {
        if (method_exists(Blacklist::class, 'addDirectory')) {
<<<<<<< HEAD
            (new Blacklist())->getBlacklistedDirectories();
            Blacklist::addDirectory(dirname((new ReflectionClass(Mockery::class))->getFileName()));
        } else {
            Blacklist::$blacklistedClassNames[Mockery::class] = 1;
=======
            (new BlackList())->getBlacklistedDirectories();
            Blacklist::addDirectory(\dirname((new \ReflectionClass(\Mockery::class))->getFileName()));
        } else {
            Blacklist::$blacklistedClassNames[\Mockery::class] = 1;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        }
    }
}
