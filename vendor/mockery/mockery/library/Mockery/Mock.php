<?php

/**
 * Mockery (https://docs.mockery.io/)
 *
 * @copyright https://github.com/mockery/mockery/blob/HEAD/COPYRIGHT.md
 * @license   https://github.com/mockery/mockery/blob/HEAD/LICENSE BSD 3-Clause License
 * @link      https://github.com/mockery/mockery for the canonical source repository
 */

namespace Mockery;

<<<<<<< HEAD
use Mockery\Container;
use Mockery\CountValidator\Exception;
use Mockery\Exception\BadMethodCallException;
use Mockery\Exception\InvalidOrderException;
use Mockery\Exception\NoMatchingExpectationException;
use Mockery\Expectation;
use Mockery\ExpectationDirector;
use Mockery\ExpectsHigherOrderMessage;
use Mockery\HigherOrderMessage;
use Mockery\LegacyMockInterface;
use Mockery\MethodCall;
use Mockery\MockInterface;
use Mockery\ReceivedMethodCalls;
use Mockery\Reflector;
use Mockery\Undefined;
use Mockery\VerificationDirector;
use Mockery\VerificationExpectation;
=======
use Mockery\Exception\BadMethodCallException;
use Mockery\ExpectsHigherOrderMessage;
use Mockery\HigherOrderMessage;
use Mockery\LegacyMockInterface;
use Mockery\MockInterface;
use Mockery\Reflector;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

#[\AllowDynamicProperties]
class Mock implements MockInterface
{
    /**
     * Stores an array of all expectation directors for this mock
     *
     * @var array
     */
<<<<<<< HEAD
    protected $_mockery_expectations = [];
=======
    protected $_mockery_expectations = array();
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Stores an initial number of expectations that can be manipulated
     * while using the getter method.
     *
     * @var int
     */
    protected $_mockery_expectations_count = 0;

    /**
     * Flag to indicate whether we can ignore method calls missing from our
     * expectations
     *
     * @var bool
     */
    protected $_mockery_ignoreMissing = false;

    /**
     * Flag to indicate whether we want to set the ignoreMissing flag on
     * mocks generated form this calls to this one
     *
     * @var bool
     */
    protected $_mockery_ignoreMissingRecursive = false;

    /**
     * Flag to indicate whether we can defer method calls missing from our
     * expectations
     *
     * @var bool
     */
    protected $_mockery_deferMissing = false;

    /**
     * Flag to indicate whether this mock was verified
     *
     * @var bool
     */
    protected $_mockery_verified = false;

    /**
     * Given name of the mock
     *
     * @var string
     */
    protected $_mockery_name = null;

    /**
     * Order number of allocation
     *
     * @var int
     */
    protected $_mockery_allocatedOrder = 0;

    /**
     * Current ordered number
     *
     * @var int
     */
    protected $_mockery_currentOrder = 0;

    /**
     * Ordered groups
     *
     * @var array
     */
<<<<<<< HEAD
    protected $_mockery_groups = [];
=======
    protected $_mockery_groups = array();
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Mock container containing this mock object
     *
<<<<<<< HEAD
     * @var Container
=======
     * @var \Mockery\Container
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    protected $_mockery_container = null;

    /**
     * Instance of a core object on which methods are called in the event
     * it has been set, and an expectation for one of the object's methods
     * does not exist. This implements a simple partial mock proxy system.
     *
     * @var object
     */
    protected $_mockery_partial = null;

    /**
     * Flag to indicate we should ignore all expectations temporarily. Used
     * mainly to prevent expectation matching when in the middle of a mock
     * object recording session.
     *
     * @var bool
     */
    protected $_mockery_disableExpectationMatching = false;

    /**
     * Stores all stubbed public methods separate from any on-object public
     * properties that may exist.
     *
     * @var array
     */
<<<<<<< HEAD
    protected $_mockery_mockableProperties = [];
=======
    protected $_mockery_mockableProperties = array();
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * @var array
     */
<<<<<<< HEAD
    protected $_mockery_mockableMethods = [];
=======
    protected $_mockery_mockableMethods = array();
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Just a local cache for this mock's target's methods
     *
     * @var \ReflectionMethod[]
     */
    protected static $_mockery_methods;

    protected $_mockery_allowMockingProtectedMethods = false;

    protected $_mockery_receivedMethodCalls;

    /**
     * If shouldIgnoreMissing is called, this value will be returned on all calls to missing methods
     * @var mixed
     */
    protected $_mockery_defaultReturnValue = null;

    /**
     * Tracks internally all the bad method call exceptions that happened during runtime
     *
     * @var array
     */
    protected $_mockery_thrownExceptions = [];

    protected $_mockery_instanceMock = true;

<<<<<<< HEAD
    /** @var null|string $parentClass */
    private $_mockery_parentClass = null;

=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    /**
     * We want to avoid constructors since class is copied to Generator.php
     * for inclusion on extending class definitions.
     *
<<<<<<< HEAD
     * @param Container $container
=======
     * @param \Mockery\Container $container
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     * @param object $partialObject
     * @param bool $instanceMock
     * @return void
     */
<<<<<<< HEAD
    public function mockery_init(?Container $container = null, $partialObject = null, $instanceMock = true)
    {
        if (null === $container) {
            $container = new Container();
        }

=======
    public function mockery_init(\Mockery\Container $container = null, $partialObject = null, $instanceMock = true)
    {
        if (is_null($container)) {
            $container = new \Mockery\Container();
        }
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $this->_mockery_container = $container;
        if (!is_null($partialObject)) {
            $this->_mockery_partial = $partialObject;
        }

        if (!\Mockery::getConfiguration()->mockingNonExistentMethodsAllowed()) {
            foreach ($this->mockery_getMethods() as $method) {
                if ($method->isPublic()) {
                    $this->_mockery_mockableMethods[] = $method->getName();
                }
            }
        }

        $this->_mockery_instanceMock = $instanceMock;
<<<<<<< HEAD

        $this->_mockery_parentClass = get_parent_class($this);
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * Set expected method calls
     *
     * @param string ...$methodNames one or many methods that are expected to be called in this mock
     *
<<<<<<< HEAD
     * @return ExpectationInterface|Expectation|HigherOrderMessage
     */
    public function shouldReceive(...$methodNames)
    {
        if ($methodNames === []) {
            return new HigherOrderMessage($this, 'shouldReceive');
        }

        foreach ($methodNames as $method) {
            if ('' === $method) {
                throw new \InvalidArgumentException('Received empty method name');
=======
     * @return \Mockery\ExpectationInterface|\Mockery\Expectation|\Mockery\HigherOrderMessage
     */
    public function shouldReceive(...$methodNames)
    {
        if (count($methodNames) === 0) {
            return new HigherOrderMessage($this, "shouldReceive");
        }

        foreach ($methodNames as $method) {
            if ("" == $method) {
                throw new \InvalidArgumentException("Received empty method name");
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            }
        }

        $self = $this;
        $allowMockingProtectedMethods = $this->_mockery_allowMockingProtectedMethods;
<<<<<<< HEAD
        return \Mockery::parseShouldReturnArgs(
            $this,
            $methodNames,
            static function ($method) use ($self, $allowMockingProtectedMethods) {
                $rm = $self->mockery_getMethod($method);
                if ($rm) {
                    if ($rm->isPrivate()) {
                        throw new \InvalidArgumentException($method . '() cannot be mocked as it is a private method');
                    }

                    if (!$allowMockingProtectedMethods && $rm->isProtected()) {
                        throw new \InvalidArgumentException($method . '() cannot be mocked as it is a protected method and mocking protected methods is not enabled for the currently used mock object. Use shouldAllowMockingProtectedMethods() to enable mocking of protected methods.');
=======

        $lastExpectation = \Mockery::parseShouldReturnArgs(
            $this,
            $methodNames,
            function ($method) use ($self, $allowMockingProtectedMethods) {
                $rm = $self->mockery_getMethod($method);
                if ($rm) {
                    if ($rm->isPrivate()) {
                        throw new \InvalidArgumentException("$method() cannot be mocked as it is a private method");
                    }
                    if (!$allowMockingProtectedMethods && $rm->isProtected()) {
                        throw new \InvalidArgumentException("$method() cannot be mocked as it is a protected method and mocking protected methods is not enabled for the currently used mock object. Use shouldAllowMockingProtectedMethods() to enable mocking of protected methods.");
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                    }
                }

                $director = $self->mockery_getExpectationsFor($method);
                if (!$director) {
<<<<<<< HEAD
                    $director = new ExpectationDirector($method, $self);
                    $self->mockery_setExpectationsFor($method, $director);
                }

                $expectation = new Expectation($self, $method);
=======
                    $director = new \Mockery\ExpectationDirector($method, $self);
                    $self->mockery_setExpectationsFor($method, $director);
                }
                $expectation = new \Mockery\Expectation($self, $method);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                $director->addExpectation($expectation);
                return $expectation;
            }
        );
<<<<<<< HEAD
=======
        return $lastExpectation;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    // start method allows
    /**
     * @param mixed $something  String method name or map of method => return
<<<<<<< HEAD
     * @return self|ExpectationInterface|Expectation|HigherOrderMessage
=======
     * @return self|\Mockery\ExpectationInterface|\Mockery\Expectation|\Mockery\HigherOrderMessage
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function allows($something = [])
    {
        if (is_string($something)) {
            return $this->shouldReceive($something);
        }

        if (empty($something)) {
            return $this->shouldReceive();
        }

        foreach ($something as $method => $returnValue) {
            $this->shouldReceive($method)->andReturn($returnValue);
        }

        return $this;
    }
<<<<<<< HEAD

    // end method allows
    // start method expects
    /**
        /**
    * @param mixed $something  String method name (optional)
     * @return ExpectationInterface|Expectation|ExpectsHigherOrderMessage
    */
=======
    // end method allows

    // start method expects
    /**
    /**
     * @param mixed $something  String method name (optional)
     * @return \Mockery\ExpectationInterface|\Mockery\Expectation|ExpectsHigherOrderMessage
     */
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function expects($something = null)
    {
        if (is_string($something)) {
            return $this->shouldReceive($something)->once();
        }

        return new ExpectsHigherOrderMessage($this);
    }
<<<<<<< HEAD

    // end method expects
=======
    // end method expects

>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    /**
     * Shortcut method for setting an expectation that a method should not be called.
     *
     * @param string ...$methodNames one or many methods that are expected not to be called in this mock
<<<<<<< HEAD
     * @return ExpectationInterface|Expectation|HigherOrderMessage
     */
    public function shouldNotReceive(...$methodNames)
    {
        if ($methodNames === []) {
            return new HigherOrderMessage($this, 'shouldNotReceive');
        }

        $expectation = call_user_func_array(function (string $methodNames) {
            return $this->shouldReceive($methodNames);
        }, $methodNames);
=======
     * @return \Mockery\ExpectationInterface|\Mockery\Expectation|\Mockery\HigherOrderMessage
     */
    public function shouldNotReceive(...$methodNames)
    {
        if (count($methodNames) === 0) {
            return new HigherOrderMessage($this, "shouldNotReceive");
        }

        $expectation = call_user_func_array(array($this, 'shouldReceive'), $methodNames);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $expectation->never();
        return $expectation;
    }

    /**
     * Allows additional methods to be mocked that do not explicitly exist on mocked class
<<<<<<< HEAD
     *
     * @param string $method name of the method to be mocked
     * @return Mock|MockInterface|LegacyMockInterface
=======
     * @param String $method name of the method to be mocked
     * @return Mock
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function shouldAllowMockingMethod($method)
    {
        $this->_mockery_mockableMethods[] = $method;
        return $this;
    }

    /**
     * Set mock to ignore unexpected methods and return Undefined class
     * @param mixed $returnValue the default return value for calls to missing functions on this mock
     * @param bool $recursive Specify if returned mocks should also have shouldIgnoreMissing set
     * @return static
     */
    public function shouldIgnoreMissing($returnValue = null, $recursive = false)
    {
        $this->_mockery_ignoreMissing = true;
        $this->_mockery_ignoreMissingRecursive = $recursive;
        $this->_mockery_defaultReturnValue = $returnValue;
        return $this;
    }

    public function asUndefined()
    {
        $this->_mockery_ignoreMissing = true;
<<<<<<< HEAD
        $this->_mockery_defaultReturnValue = new Undefined();
=======
        $this->_mockery_defaultReturnValue = new \Mockery\Undefined();
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this;
    }

    /**
     * @return static
     */
    public function shouldAllowMockingProtectedMethods()
    {
        if (!\Mockery::getConfiguration()->mockingNonExistentMethodsAllowed()) {
            foreach ($this->mockery_getMethods() as $method) {
                if ($method->isProtected()) {
                    $this->_mockery_mockableMethods[] = $method->getName();
                }
            }
        }

        $this->_mockery_allowMockingProtectedMethods = true;
        return $this;
    }


    /**
     * Set mock to defer unexpected methods to it's parent
     *
     * This is particularly useless for this class, as it doesn't have a parent,
     * but included for completeness
     *
     * @deprecated 2.0.0 Please use makePartial() instead
     *
     * @return static
     */
    public function shouldDeferMissing()
    {
        return $this->makePartial();
    }

    /**
     * Set mock to defer unexpected methods to it's parent
     *
     * It was an alias for shouldDeferMissing(), which will be removed
     * in 2.0.0.
     *
     * @return static
     */
    public function makePartial()
    {
        $this->_mockery_deferMissing = true;
        return $this;
    }

    /**
     * In the event shouldReceive() accepting one or more methods/returns,
     * this method will switch them from normal expectations to default
     * expectations
     *
     * @return self
     */
    public function byDefault()
    {
        foreach ($this->_mockery_expectations as $director) {
            $exps = $director->getExpectations();
            foreach ($exps as $exp) {
                $exp->byDefault();
            }
        }
<<<<<<< HEAD

=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this;
    }

    /**
     * Capture calls to this mock
     */
    public function __call($method, array $args)
    {
        return $this->_mockery_handleMethodCall($method, $args);
    }

    public static function __callStatic($method, array $args)
    {
        return self::_mockery_handleStaticMethodCall($method, $args);
    }

    /**
     * Forward calls to this magic method to the __call method
     */
<<<<<<< HEAD
    #[\ReturnTypeWillChange]
    public function __toString()
    {
        return $this->__call('__toString', []);
=======
    public function __toString()
    {
        return $this->__call('__toString', array());
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * Iterate across all expectation directors and validate each
     *
<<<<<<< HEAD
     * @throws Exception
=======
     * @throws \Mockery\CountValidator\Exception
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     * @return void
     */
    public function mockery_verify()
    {
        if ($this->_mockery_verified) {
            return;
        }
<<<<<<< HEAD

        if (property_exists($this, '_mockery_ignoreVerification') && $this->_mockery_ignoreVerification !== null
            && $this->_mockery_ignoreVerification == true) {
            return;
        }

=======
        if (isset($this->_mockery_ignoreVerification)
            && $this->_mockery_ignoreVerification == true) {
            return;
        }
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $this->_mockery_verified = true;
        foreach ($this->_mockery_expectations as $director) {
            $director->verify();
        }
    }

    /**
     * Gets a list of exceptions thrown by this mock
     *
     * @return array
     */
    public function mockery_thrownExceptions()
    {
        return $this->_mockery_thrownExceptions;
    }

    /**
     * Tear down tasks for this mock
     *
     * @return void
     */
    public function mockery_teardown()
    {
    }

    /**
     * Fetch the next available allocation order number
     *
     * @return int
     */
    public function mockery_allocateOrder()
    {
<<<<<<< HEAD
        ++$this->_mockery_allocatedOrder;
=======
        $this->_mockery_allocatedOrder += 1;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this->_mockery_allocatedOrder;
    }

    /**
     * Set ordering for a group
     *
     * @param mixed $group
     * @param int $order
     */
    public function mockery_setGroup($group, $order)
    {
        $this->_mockery_groups[$group] = $order;
    }

    /**
     * Fetch array of ordered groups
     *
     * @return array
     */
    public function mockery_getGroups()
    {
        return $this->_mockery_groups;
    }

    /**
     * Set current ordered number
     *
     * @param int $order
     */
    public function mockery_setCurrentOrder($order)
    {
        $this->_mockery_currentOrder = $order;
        return $this->_mockery_currentOrder;
    }

    /**
     * Get current ordered number
     *
     * @return int
     */
    public function mockery_getCurrentOrder()
    {
        return $this->_mockery_currentOrder;
    }

    /**
     * Validate the current mock's ordering
     *
     * @param string $method
     * @param int $order
     * @throws \Mockery\Exception
     * @return void
     */
    public function mockery_validateOrder($method, $order)
    {
        if ($order < $this->_mockery_currentOrder) {
<<<<<<< HEAD
            $exception = new InvalidOrderException(
                'Method ' . self::class . '::' . $method . '()'
=======
            $exception = new \Mockery\Exception\InvalidOrderException(
                'Method ' . __CLASS__ . '::' . $method . '()'
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                . ' called out of order: expected order '
                . $order . ', was ' . $this->_mockery_currentOrder
            );
            $exception->setMock($this)
                ->setMethodName($method)
                ->setExpectedOrder($order)
                ->setActualOrder($this->_mockery_currentOrder);
            throw $exception;
        }
<<<<<<< HEAD

=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $this->mockery_setCurrentOrder($order);
    }

    /**
     * Gets the count of expectations for this mock
     *
     * @return int
     */
    public function mockery_getExpectationCount()
    {
        $count = $this->_mockery_expectations_count;
        foreach ($this->_mockery_expectations as $director) {
            $count += $director->getExpectationCount();
        }
<<<<<<< HEAD

=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $count;
    }

    /**
     * Return the expectations director for the given method
     *
     * @var string $method
<<<<<<< HEAD
     * @return ExpectationDirector|null
     */
    public function mockery_setExpectationsFor($method, ExpectationDirector $director)
=======
     * @return \Mockery\ExpectationDirector|null
     */
    public function mockery_setExpectationsFor($method, \Mockery\ExpectationDirector $director)
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    {
        $this->_mockery_expectations[$method] = $director;
    }

    /**
     * Return the expectations director for the given method
     *
     * @var string $method
<<<<<<< HEAD
     * @return ExpectationDirector|null
=======
     * @return \Mockery\ExpectationDirector|null
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function mockery_getExpectationsFor($method)
    {
        if (isset($this->_mockery_expectations[$method])) {
            return $this->_mockery_expectations[$method];
        }
    }

    /**
     * Find an expectation matching the given method and arguments
     *
     * @var string $method
     * @var array $args
<<<<<<< HEAD
     * @return Expectation|null
=======
     * @return \Mockery\Expectation|null
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function mockery_findExpectation($method, array $args)
    {
        if (!isset($this->_mockery_expectations[$method])) {
            return null;
        }
<<<<<<< HEAD

=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $director = $this->_mockery_expectations[$method];

        return $director->findExpectation($args);
    }

    /**
     * Return the container for this mock
     *
<<<<<<< HEAD
     * @return Container
=======
     * @return \Mockery\Container
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function mockery_getContainer()
    {
        return $this->_mockery_container;
    }

    /**
     * Return the name for this mock
     *
     * @return string
     */
    public function mockery_getName()
    {
<<<<<<< HEAD
        return self::class;
=======
        return __CLASS__;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * @return array
     */
    public function mockery_getMockableProperties()
    {
        return $this->_mockery_mockableProperties;
    }

    public function __isset($name)
    {
<<<<<<< HEAD
        if (false !== stripos($name, '_mockery_')) {
            return false;
        }

        if (!$this->_mockery_parentClass) {
            return false;
        }

        if (!method_exists($this->_mockery_parentClass, '__isset')) {
            return false;
        }

        return call_user_func($this->_mockery_parentClass . '::__isset', $name);
=======
        if (false === stripos($name, '_mockery_') && get_parent_class($this) && method_exists(get_parent_class($this), '__isset')) {
            return call_user_func(get_parent_class($this) . '::__isset', $name);
        }

        return false;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    public function mockery_getExpectations()
    {
        return $this->_mockery_expectations;
    }

    /**
     * Calls a parent class method and returns the result. Used in a passthru
     * expectation where a real return value is required while still taking
     * advantage of expectation matching and call count verification.
     *
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public function mockery_callSubjectMethod($name, array $args)
    {
<<<<<<< HEAD
        if (!method_exists($this, $name) && $this->_mockery_parentClass && method_exists($this->_mockery_parentClass, '__call')) {
            return call_user_func($this->_mockery_parentClass . '::__call', $name, $args);
        }

        return call_user_func_array($this->_mockery_parentClass . '::' . $name, $args);
=======
        if (!method_exists($this, $name) && get_parent_class($this) && method_exists(get_parent_class($this), '__call')) {
            return call_user_func(get_parent_class($this) . '::__call', $name, $args);
        }
        return call_user_func_array(get_parent_class($this) . '::' . $name, $args);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * @return string[]
     */
    public function mockery_getMockableMethods()
    {
        return $this->_mockery_mockableMethods;
    }

    /**
     * @return bool
     */
    public function mockery_isAnonymous()
    {
        $rfc = new \ReflectionClass($this);

        // PHP 8 has Stringable interface
<<<<<<< HEAD
        $interfaces = array_filter($rfc->getInterfaces(), static function ($i) {
=======
        $interfaces = array_filter($rfc->getInterfaces(), function ($i) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            return $i->getName() !== 'Stringable';
        });

        return false === $rfc->getParentClass() && 2 === count($interfaces);
    }

    public function mockery_isInstance()
    {
        return $this->_mockery_instanceMock;
    }

    public function __wakeup()
    {
        /**
         * This does not add __wakeup method support. It's a blind method and any
         * expected __wakeup work will NOT be performed. It merely cuts off
         * annoying errors where a __wakeup exists but is not essential when
         * mocking
         */
    }

    public function __destruct()
    {
        /**
         * Overrides real class destructor in case if class was created without original constructor
         */
    }

    public function mockery_getMethod($name)
    {
        foreach ($this->mockery_getMethods() as $method) {
            if ($method->getName() == $name) {
                return $method;
            }
        }

        return null;
    }

    /**
     * @param string $name Method name.
     *
     * @return mixed Generated return value based on the declared return value of the named method.
     */
    public function mockery_returnValueForMethod($name)
    {
        $rm = $this->mockery_getMethod($name);

        if ($rm === null) {
            return null;
        }

        $returnType = Reflector::getSimplestReturnType($rm);

        switch ($returnType) {
            case null:     return null;
            case 'string': return '';
            case 'int':    return 0;
            case 'float':  return 0.0;
            case 'bool':   return false;
            case 'true':   return true;
            case 'false':   return false;

            case 'array':
            case 'iterable':
                return [];

            case 'callable':
            case '\Closure':
<<<<<<< HEAD
                return static function () : void {
=======
                return function () {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                };

            case '\Traversable':
            case '\Generator':
<<<<<<< HEAD
                $generator = static function () {
                    yield;
                };
=======
                $generator = function () { yield; };
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                return $generator();

            case 'void':
                return null;

            case 'static':
                return $this;

            case 'object':
                $mock = \Mockery::mock();
                if ($this->_mockery_ignoreMissingRecursive) {
                    $mock->shouldIgnoreMissing($this->_mockery_defaultReturnValue, true);
                }
<<<<<<< HEAD

=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                return $mock;

            default:
                $mock = \Mockery::mock($returnType);
                if ($this->_mockery_ignoreMissingRecursive) {
                    $mock->shouldIgnoreMissing($this->_mockery_defaultReturnValue, true);
                }
<<<<<<< HEAD

=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                return $mock;
        }
    }

    public function shouldHaveReceived($method = null, $args = null)
    {
        if ($method === null) {
<<<<<<< HEAD
            return new HigherOrderMessage($this, 'shouldHaveReceived');
        }

        $expectation = new VerificationExpectation($this, $method);
        if (null !== $args) {
            $expectation->withArgs($args);
        }

        $expectation->atLeast()->once();
        $director = new VerificationDirector($this->_mockery_getReceivedMethodCalls(), $expectation);
        ++$this->_mockery_expectations_count;
=======
            return new HigherOrderMessage($this, "shouldHaveReceived");
        }

        $expectation = new \Mockery\VerificationExpectation($this, $method);
        if (null !== $args) {
            $expectation->withArgs($args);
        }
        $expectation->atLeast()->once();
        $director = new \Mockery\VerificationDirector($this->_mockery_getReceivedMethodCalls(), $expectation);
        $this->_mockery_expectations_count++;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $director->verify();
        return $director;
    }

    public function shouldHaveBeenCalled()
    {
<<<<<<< HEAD
        return $this->shouldHaveReceived('__invoke');
=======
        return $this->shouldHaveReceived("__invoke");
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    public function shouldNotHaveReceived($method = null, $args = null)
    {
        if ($method === null) {
<<<<<<< HEAD
            return new HigherOrderMessage($this, 'shouldNotHaveReceived');
        }

        $expectation = new VerificationExpectation($this, $method);
        if (null !== $args) {
            $expectation->withArgs($args);
        }

        $expectation->never();
        $director = new VerificationDirector($this->_mockery_getReceivedMethodCalls(), $expectation);
        ++$this->_mockery_expectations_count;
=======
            return new HigherOrderMessage($this, "shouldNotHaveReceived");
        }

        $expectation = new \Mockery\VerificationExpectation($this, $method);
        if (null !== $args) {
            $expectation->withArgs($args);
        }
        $expectation->never();
        $director = new \Mockery\VerificationDirector($this->_mockery_getReceivedMethodCalls(), $expectation);
        $this->_mockery_expectations_count++;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $director->verify();
        return null;
    }

<<<<<<< HEAD
    public function shouldNotHaveBeenCalled(?array $args = null)
    {
        return $this->shouldNotHaveReceived('__invoke', $args);
=======
    public function shouldNotHaveBeenCalled(array $args = null)
    {
        return $this->shouldNotHaveReceived("__invoke", $args);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    protected static function _mockery_handleStaticMethodCall($method, array $args)
    {
<<<<<<< HEAD
        $associatedRealObject = \Mockery::fetchMock(self::class);
        try {
            return $associatedRealObject->__call($method, $args);
        } catch (BadMethodCallException $badMethodCallException) {
=======
        $associatedRealObject = \Mockery::fetchMock(__CLASS__);
        try {
            return $associatedRealObject->__call($method, $args);
        } catch (BadMethodCallException $e) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            throw new BadMethodCallException(
                'Static method ' . $associatedRealObject->mockery_getName() . '::' . $method
                . '() does not exist on this mock object',
                0,
<<<<<<< HEAD
                $badMethodCallException
=======
                $e
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            );
        }
    }

    protected function _mockery_getReceivedMethodCalls()
    {
<<<<<<< HEAD
        return $this->_mockery_receivedMethodCalls ?: $this->_mockery_receivedMethodCalls = new ReceivedMethodCalls();
=======
        return $this->_mockery_receivedMethodCalls ?: $this->_mockery_receivedMethodCalls = new \Mockery\ReceivedMethodCalls();
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * Called when an instance Mock was created and its constructor is getting called
     *
     * @see \Mockery\Generator\StringManipulation\Pass\InstanceMockPass
     * @param array $args
     */
    protected function _mockery_constructorCalled(array $args)
    {
        if (!isset($this->_mockery_expectations['__construct']) /* _mockery_handleMethodCall runs the other checks */) {
            return;
        }
<<<<<<< HEAD

=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $this->_mockery_handleMethodCall('__construct', $args);
    }

    protected function _mockery_findExpectedMethodHandler($method)
    {
        if (isset($this->_mockery_expectations[$method])) {
            return $this->_mockery_expectations[$method];
        }

        $lowerCasedMockeryExpectations = array_change_key_case($this->_mockery_expectations, CASE_LOWER);
        $lowerCasedMethod = strtolower($method);

<<<<<<< HEAD
        return $lowerCasedMockeryExpectations[$lowerCasedMethod] ?? null;
=======
        if (isset($lowerCasedMockeryExpectations[$lowerCasedMethod])) {
            return $lowerCasedMockeryExpectations[$lowerCasedMethod];
        }

        return null;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    protected function _mockery_handleMethodCall($method, array $args)
    {
<<<<<<< HEAD
        $this->_mockery_getReceivedMethodCalls()->push(new MethodCall($method, $args));
=======
        $this->_mockery_getReceivedMethodCalls()->push(new \Mockery\MethodCall($method, $args));
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

        $rm = $this->mockery_getMethod($method);
        if ($rm && $rm->isProtected() && !$this->_mockery_allowMockingProtectedMethods) {
            if ($rm->isAbstract()) {
                return;
            }

            try {
                $prototype = $rm->getPrototype();
                if ($prototype->isAbstract()) {
                    return;
                }
            } catch (\ReflectionException $re) {
                // noop - there is no hasPrototype method
            }

<<<<<<< HEAD
            if (null === $this->_mockery_parentClass) {
                $this->_mockery_parentClass = get_parent_class($this);
            }

            return call_user_func_array($this->_mockery_parentClass . '::' . $method, $args);
=======
            return call_user_func_array(get_parent_class($this) . '::' . $method, $args);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        }

        $handler = $this->_mockery_findExpectedMethodHandler($method);

        if ($handler !== null && !$this->_mockery_disableExpectationMatching) {
            try {
                return $handler->call($args);
<<<<<<< HEAD
            } catch (NoMatchingExpectationException $e) {
=======
            } catch (\Mockery\Exception\NoMatchingExpectationException $e) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                if (!$this->_mockery_ignoreMissing && !$this->_mockery_deferMissing) {
                    throw $e;
                }
            }
        }

        if (!is_null($this->_mockery_partial) &&
<<<<<<< HEAD
            (method_exists($this->_mockery_partial, $method) || method_exists($this->_mockery_partial, '__call'))) {
            return $this->_mockery_partial->{$method}(...$args);
        }

        if ($this->_mockery_deferMissing && is_callable($this->_mockery_parentClass . '::' . $method)
            && (!$this->hasMethodOverloadingInParentClass() || ($this->_mockery_parentClass && method_exists($this->_mockery_parentClass, $method)))) {
            return call_user_func_array($this->_mockery_parentClass . '::' . $method, $args);
        }

        if ($this->_mockery_deferMissing && $this->_mockery_parentClass && method_exists($this->_mockery_parentClass, '__call')) {
            return call_user_func($this->_mockery_parentClass . '::__call', $method, $args);
        }

        if ($method === '__toString') {
            // __toString is special because we force its addition to the class API regardless of the
            // original implementation.  Thus, we should always return a string rather than honor
            // _mockery_ignoreMissing and break the API with an error.
            return sprintf('%s#%s', self::class, spl_object_hash($this));
        }

        if ($this->_mockery_ignoreMissing && (\Mockery::getConfiguration()->mockingNonExistentMethodsAllowed() || (!is_null($this->_mockery_partial) && method_exists($this->_mockery_partial, $method)) || is_callable($this->_mockery_parentClass . '::' . $method))) {
            if ($this->_mockery_defaultReturnValue instanceof Undefined) {
                return $this->_mockery_defaultReturnValue->{$method}(...$args);
            }

            if (null === $this->_mockery_defaultReturnValue) {
                return $this->mockery_returnValueForMethod($method);
            }

            return $this->_mockery_defaultReturnValue;
        }

        $message = 'Method ' . self::class . '::' . $method .
            '() does not exist on this mock object';

        if (!is_null($rm)) {
            $message = 'Received ' . self::class .
=======
            (method_exists($this->_mockery_partial, $method) || method_exists($this->_mockery_partial, '__call'))
        ) {
            return call_user_func_array(array($this->_mockery_partial, $method), $args);
        } elseif ($this->_mockery_deferMissing && is_callable(get_parent_class($this) . '::' . $method)
            && (!$this->hasMethodOverloadingInParentClass() || (get_parent_class($this) && method_exists(get_parent_class($this), $method)))) {
            return call_user_func_array(get_parent_class($this) . '::' . $method, $args);
        } elseif ($this->_mockery_deferMissing && get_parent_class($this) && method_exists(get_parent_class($this), '__call')) {
            return call_user_func(get_parent_class($this) . '::__call', $method, $args);
        } elseif ($method == '__toString') {
            // __toString is special because we force its addition to the class API regardless of the
            // original implementation.  Thus, we should always return a string rather than honor
            // _mockery_ignoreMissing and break the API with an error.
            return sprintf("%s#%s", __CLASS__, spl_object_hash($this));
        } elseif ($this->_mockery_ignoreMissing) {
            if (\Mockery::getConfiguration()->mockingNonExistentMethodsAllowed() || (!is_null($this->_mockery_partial) && method_exists($this->_mockery_partial, $method)) || is_callable(get_parent_class($this) . '::' . $method)) {
                if ($this->_mockery_defaultReturnValue instanceof \Mockery\Undefined) {
                    return call_user_func_array(array($this->_mockery_defaultReturnValue, $method), $args);
                } elseif (null === $this->_mockery_defaultReturnValue) {
                    return $this->mockery_returnValueForMethod($method);
                }

                return $this->_mockery_defaultReturnValue;
            }
        }

        $message = 'Method ' . __CLASS__ . '::' . $method .
            '() does not exist on this mock object';

        if (!is_null($rm)) {
            $message = 'Received ' . __CLASS__ .
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                '::' . $method . '(), but no expectations were specified';
        }

        $bmce = new BadMethodCallException($message);
        $this->_mockery_thrownExceptions[] = $bmce;
        throw $bmce;
    }

    /**
     * Uses reflection to get the list of all
     * methods within the current mock object
     *
     * @return array
     */
    protected function mockery_getMethods()
    {
        if (static::$_mockery_methods && \Mockery::getConfiguration()->reflectionCacheEnabled()) {
            return static::$_mockery_methods;
        }

<<<<<<< HEAD
        if ($this->_mockery_partial !== null) {
=======
        if (isset($this->_mockery_partial)) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            $reflected = new \ReflectionObject($this->_mockery_partial);
        } else {
            $reflected = new \ReflectionClass($this);
        }

        return static::$_mockery_methods = $reflected->getMethods();
    }

    private function hasMethodOverloadingInParentClass()
    {
        // if there's __call any name would be callable
<<<<<<< HEAD
        return is_callable($this->_mockery_parentClass . '::aFunctionNameThatNoOneWouldEverUseInRealLife12345');
=======
        return is_callable(get_parent_class($this) . '::aFunctionNameThatNoOneWouldEverUseInRealLife12345');
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * @return array
     */
    private function getNonPublicMethods()
    {
        return array_map(
<<<<<<< HEAD
            static function ($method) {
                return $method->getName();
            },
            array_filter($this->mockery_getMethods(), static function ($method) {
=======
            function ($method) {
                return $method->getName();
            },
            array_filter($this->mockery_getMethods(), function ($method) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                return !$method->isPublic();
            })
        );
    }
}
