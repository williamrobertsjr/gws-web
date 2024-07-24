<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Framework\MockObject\Rule;

use function count;
use function gettype;
use function is_iterable;
use function sprintf;
use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\Constraint\IsEqual;
<<<<<<< HEAD
use PHPUnit\Framework\Exception;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\InvalidParameterGroupException;
use PHPUnit\Framework\MockObject\Invocation as BaseInvocation;
use SebastianBergmann\RecursionContext\InvalidArgumentException;
=======
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\InvalidParameterGroupException;
use PHPUnit\Framework\MockObject\Invocation as BaseInvocation;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 *
 * @deprecated
 */
final class ConsecutiveParameters implements ParametersRule
{
    /**
     * @var array
     */
    private $parameterGroups = [];

    /**
     * @var array
     */
    private $invocations = [];

    /**
<<<<<<< HEAD
     * @throws Exception
=======
     * @throws \PHPUnit\Framework\Exception
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct(array $parameterGroups)
    {
        foreach ($parameterGroups as $index => $parameters) {
            if (!is_iterable($parameters)) {
                throw new InvalidParameterGroupException(
                    sprintf(
                        'Parameter group #%d must be an array or Traversable, got %s',
                        $index,
                        gettype($parameters),
                    ),
                );
            }

            foreach ($parameters as $parameter) {
                if (!$parameter instanceof Constraint) {
                    $parameter = new IsEqual($parameter);
                }

                $this->parameterGroups[$index][] = $parameter;
            }
        }
    }

    public function toString(): string
    {
        return 'with consecutive parameters';
    }

    /**
<<<<<<< HEAD
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
=======
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function apply(BaseInvocation $invocation): void
    {
        $this->invocations[] = $invocation;
        $callIndex           = count($this->invocations) - 1;

        $this->verifyInvocation($invocation, $callIndex);
    }

    /**
<<<<<<< HEAD
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
=======
     * @throws \PHPUnit\Framework\ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function verify(): void
    {
        foreach ($this->invocations as $callIndex => $invocation) {
            $this->verifyInvocation($invocation, $callIndex);
        }
    }

    /**
     * Verify a single invocation.
     *
     * @param int $callIndex
     *
<<<<<<< HEAD
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
=======
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ExpectationFailedException
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    private function verifyInvocation(BaseInvocation $invocation, $callIndex): void
    {
        if (!isset($this->parameterGroups[$callIndex])) {
            // no parameter assertion for this call index
            return;
        }

        $parameters = $this->parameterGroups[$callIndex];

        if (count($invocation->getParameters()) < count($parameters)) {
            throw new ExpectationFailedException(
                sprintf(
                    'Parameter count for invocation %s is too low.',
                    $invocation->toString(),
                ),
            );
        }

        foreach ($parameters as $i => $parameter) {
            $parameter->evaluate(
                $invocation->getParameters()[$i],
                sprintf(
                    'Parameter %s for invocation #%d %s does not match expected ' .
                    'value.',
                    $i,
                    $callIndex,
                    $invocation->toString(),
                ),
            );
        }
    }
}
