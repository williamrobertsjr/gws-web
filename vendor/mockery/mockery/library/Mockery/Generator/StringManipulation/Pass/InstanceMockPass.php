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
use function strrpos;
use function substr;

class InstanceMockPass implements Pass
{
    public const INSTANCE_MOCK_CODE = <<<MOCK
=======

class InstanceMockPass
{
    const INSTANCE_MOCK_CODE = <<<MOCK
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    protected \$_mockery_ignoreVerification = true;

    public function __construct()
    {
        \$this->_mockery_ignoreVerification = false;
        \$associatedRealObject = \Mockery::fetchMock(__CLASS__);

        foreach (get_object_vars(\$this) as \$attr => \$val) {
            if (\$attr !== "_mockery_ignoreVerification" && \$attr !== "_mockery_expectations") {
                \$this->\$attr = \$associatedRealObject->\$attr;
            }
        }

        \$directors = \$associatedRealObject->mockery_getExpectations();
        foreach (\$directors as \$method=>\$director) {
            // get the director method needed
            \$existingDirector = \$this->mockery_getExpectationsFor(\$method);
            if (!\$existingDirector) {
                \$existingDirector = new \Mockery\ExpectationDirector(\$method, \$this);
                \$this->mockery_setExpectationsFor(\$method, \$existingDirector);
            }
            \$expectations = \$director->getExpectations();
            foreach (\$expectations as \$expectation) {
                \$clonedExpectation = clone \$expectation;
                \$existingDirector->addExpectation(\$clonedExpectation);
            }
            \$defaultExpectations = \$director->getDefaultExpectations();
            foreach (array_reverse(\$defaultExpectations) as \$expectation) {
                \$clonedExpectation = clone \$expectation;
                \$existingDirector->addExpectation(\$clonedExpectation);
                \$existingDirector->makeExpectationDefault(\$clonedExpectation);
            }
        }
        \Mockery::getContainer()->rememberMock(\$this);

        \$this->_mockery_constructorCalled(func_get_args());
    }
MOCK;

<<<<<<< HEAD
    /**
     * @param  string $code
     * @return string
     */
    public function apply($code, MockConfiguration $config)
    {
        if ($config->isInstanceMock()) {
            return $this->appendToClass($code, static::INSTANCE_MOCK_CODE);
=======
    public function apply($code, MockConfiguration $config)
    {
        if ($config->isInstanceMock()) {
            $code = $this->appendToClass($code, static::INSTANCE_MOCK_CODE);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        }

        return $code;
    }

    protected function appendToClass($class, $code)
    {
<<<<<<< HEAD
        $lastBrace = strrpos($class, '}');
        return substr($class, 0, $lastBrace) . $code . "\n    }\n";
=======
        $lastBrace = strrpos($class, "}");
        $class = substr($class, 0, $lastBrace) . $code . "\n    }\n";
        return $class;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }
}
