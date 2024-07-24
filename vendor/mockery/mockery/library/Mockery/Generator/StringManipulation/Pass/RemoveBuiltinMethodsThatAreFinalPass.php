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

namespace Mockery\Generator\StringManipulation\Pass;

use Mockery\Generator\MockConfiguration;
<<<<<<< HEAD
use Mockery\Generator\TargetClassInterface;
use function preg_replace;
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

/**
 * The standard Mockery\Mock class includes some methods to ease mocking, such
 * as __wakeup, however if the target has a final __wakeup method, it can't be
 * mocked. This pass removes the builtin methods where they are final on the
 * target
 */
<<<<<<< HEAD
class RemoveBuiltinMethodsThatAreFinalPass implements Pass
{
    protected $methods = [
        '__wakeup' => '/public function __wakeup\(\)\s+\{.*?\}/sm',
        '__toString' => '/public function __toString\(\)\s+(:\s+string)?\s*\{.*?\}/sm',
    ];

    /**
     * @param  string $code
     * @return string
     */
=======
class RemoveBuiltinMethodsThatAreFinalPass
{
    protected $methods = array(
        '__wakeup' => '/public function __wakeup\(\)\s+\{.*?\}/sm',
        '__toString' => '/public function __toString\(\)\s+(:\s+string)?\s*\{.*?\}/sm',
    );

>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function apply($code, MockConfiguration $config)
    {
        $target = $config->getTargetClass();

<<<<<<< HEAD
        if (! $target instanceof TargetClassInterface) {
=======
        if (!$target) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            return $code;
        }

        foreach ($target->getMethods() as $method) {
<<<<<<< HEAD
            if (! $method->isFinal()) {
                continue;
            }

            if (! isset($this->methods[$method->getName()])) {
                continue;
            }

            $code = preg_replace($this->methods[$method->getName()], '', $code);
=======
            if ($method->isFinal() && isset($this->methods[$method->getName()])) {
                $code = preg_replace($this->methods[$method->getName()], '', $code);
            }
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        }

        return $code;
    }
}
