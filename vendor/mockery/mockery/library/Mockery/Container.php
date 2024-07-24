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

<<<<<<< HEAD
use Closure;
use Exception as PHPException;
use Mockery;
use Mockery\Exception\InvalidOrderException;
use Mockery\Exception\RuntimeException;
use Mockery\Generator\Generator;
use Mockery\Generator\MockConfigurationBuilder;
use Mockery\Loader\Loader as LoaderInterface;
use ReflectionClass;
use ReflectionException;
use Throwable;

use function array_filter;
use function array_key_exists;
use function array_keys;
use function array_pop;
use function array_shift;
use function array_values;
use function class_exists;
use function count;
use function explode;
use function get_class;
use function interface_exists;
use function is_callable;
use function is_object;
use function is_string;
use function md5;
use function preg_grep;
use function preg_match;
use function range;
use function reset;
use function rtrim;
use function sprintf;
use function str_replace;
use function strlen;
use function strpos;
use function strtolower;
use function substr;
use function trait_exists;

/**
 * Container for mock objects
 *
 * @template TMockObject of object
 */
class Container
{
    public const BLOCKS = Mockery::BLOCKS;
=======
use Mockery\Generator\Generator;
use Mockery\Generator\MockConfigurationBuilder;
use Mockery\Loader\Loader as LoaderInterface;

class Container
{
    const BLOCKS = \Mockery::BLOCKS;

    /**
     * Store of mock objects
     *
     * @var array
     */
    protected $_mocks = array();
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Order number of allocation
     *
     * @var int
     */
    protected $_allocatedOrder = 0;

    /**
     * Current ordered number
     *
     * @var int
     */
    protected $_currentOrder = 0;

    /**
<<<<<<< HEAD
     * @var Generator
     */
    protected $_generator;

    /**
     * Ordered groups
     *
     * @var array<string,int>
     */
    protected $_groups = [];

    /**
     * @var LoaderInterface
     */
    protected $_loader;

    /**
     * Store of mock objects
     *
     * @var array<class-string<LegacyMockInterface&MockInterface&TMockObject>|array-key,LegacyMockInterface&MockInterface&TMockObject>
     */
    protected $_mocks = [];

    /**
     * @var array<string,string>
     */
    protected $_namedMocks = [];

    /**
     * @var Instantiator
     */
    protected $instantiator;

    public function __construct(?Generator $generator = null, ?LoaderInterface $loader = null, ?Instantiator $instantiator = null)
    {
        $this->_generator = $generator instanceof Generator ? $generator : Mockery::getDefaultGenerator();
        $this->_loader = $loader instanceof LoaderInterface ? $loader : Mockery::getDefaultLoader();
        $this->instantiator = $instantiator instanceof Instantiator ? $instantiator : new Instantiator();
    }

    /**
     * Return a specific remembered mock according to the array index it
     * was stored to in this container instance
     *
     * @template TMock of object
     *
     * @param class-string<TMock> $reference
     *
     * @return null|(LegacyMockInterface&MockInterface&TMock)
     */
    public function fetchMock($reference)
    {
        return $this->_mocks[$reference] ?? null;
    }

    /**
     * @return Generator
     */
    public function getGenerator()
    {
        return $this->_generator;
    }

    /**
     * @param string $method
     * @param string $parent
     *
     * @return null|string
     */
    public function getKeyOfDemeterMockFor($method, $parent)
    {
        $keys = array_keys($this->_mocks);

        $match = preg_grep('/__demeter_' . md5($parent) . sprintf('_%s$/', $method), $keys);
        if ($match === false) {
            return null;
        }

        if ($match === []) {
            return null;
        }

        return array_values($match)[0];
    }

    /**
     * @return LoaderInterface
     */
    public function getLoader()
    {
        return $this->_loader;
    }

    /**
     * @template TMock of object
     * @return array<class-string<LegacyMockInterface&MockInterface&TMockObject>|array-key,LegacyMockInterface&MockInterface&TMockObject>
     */
    public function getMocks()
    {
        return $this->_mocks;
    }

    /**
     * @return void
     */
    public function instanceMock()
    {
    }

    /**
     * see http://php.net/manual/en/language.oop5.basic.php
     *
     * @param string $className
     *
     * @return bool
     */
    public function isValidClassName($className)
    {
        if ($className[0] === '\\') {
            $className = substr($className, 1); // remove the first backslash
        }

        // all the namespaces and class name should match the regex
        return array_filter(
            explode('\\', $className),
            static function ($name): bool {
                return ! preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $name);
            }
        ) === [];
=======
     * Ordered groups
     *
     * @var array
     */
    protected $_groups = array();

    /**
     * @var Generator
     */
    protected $_generator;

    /**
     * @var LoaderInterface
     */
    protected $_loader;

    /**
     * @var array
     */
    protected $_namedMocks = array();

    public function __construct(Generator $generator = null, LoaderInterface $loader = null)
    {
        $this->_generator = $generator ?: \Mockery::getDefaultGenerator();
        $this->_loader = $loader ?: \Mockery::getDefaultLoader();
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * Generates a new mock object for this container
     *
     * I apologies in advance for this. A God Method just fits the API which
     * doesn't require differentiating between classes, interfaces, abstracts,
     * names or partials - just so long as it's something that can be mocked.
     * I'll refactor it one day so it's easier to follow.
     *
<<<<<<< HEAD
     * @template TMock of object
     *
     * @param array<class-string<TMock>|TMock|Closure(LegacyMockInterface&MockInterface&TMock):LegacyMockInterface&MockInterface&TMock|array<TMock>> $args
     *
     * @throws ReflectionException|RuntimeException
     *
     * @return LegacyMockInterface&MockInterface&TMock
     */
    public function mock(...$args)
    {
        /** @var null|MockConfigurationBuilder $builder */
        $builder = null;
        /** @var null|callable $expectationClosure */
        $expectationClosure = null;
        $partialMethods = null;
        $quickDefinitions = [];
        $constructorArgs = null;
        $blocks = [];

        if (count($args) > 1) {
            $finalArg = array_pop($args);

            if (is_callable($finalArg) && is_object($finalArg)) {
                $expectationClosure = $finalArg;
            } else {
                $args[] = $finalArg;
            }
        }

        foreach ($args as $k => $arg) {
            if ($arg instanceof MockConfigurationBuilder) {
                $builder = $arg;

                unset($args[$k]);
            }
        }

        reset($args);

        $builder = $builder ?? new MockConfigurationBuilder();
        $mockeryConfiguration = Mockery::getConfiguration();
        $builder->setParameterOverrides($mockeryConfiguration->getInternalClassMethodParamMaps());
        $builder->setConstantsMap($mockeryConfiguration->getConstantsMap());

        while ($args !== []) {
            $arg = array_shift($args);

=======
     * @param array ...$args
     *
     * @return Mock
     * @throws Exception\RuntimeException
     */
    public function mock(...$args)
    {
        $expectationClosure = null;
        $quickdefs = array();
        $constructorArgs = null;
        $blocks = array();
        $class = null;

        if (count($args) > 1) {
            $finalArg = end($args);
            reset($args);
            if (is_callable($finalArg) && is_object($finalArg)) {
                $expectationClosure = array_pop($args);
            }
        }

        $builder = new MockConfigurationBuilder();

        foreach ($args as $k => $arg) {
            if ($arg instanceof MockConfigurationBuilder) {
                $builder = $arg;
                unset($args[$k]);
            }
        }
        reset($args);

        $builder->setParameterOverrides(\Mockery::getConfiguration()->getInternalClassMethodParamMaps());
        $builder->setConstantsMap(\Mockery::getConfiguration()->getConstantsMap());

        while (count($args) > 0) {
            $arg = array_shift($args);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            // check for multiple interfaces
            if (is_string($arg)) {
                foreach (explode('|', $arg) as $type) {
                    if ($arg === 'null') {
                        // skip PHP 8 'null's
<<<<<<< HEAD
                        continue;
                    }

                    if (strpos($type, ',') && !strpos($type, ']')) {
                        $interfaces = explode(',', str_replace(' ', '', $type));

                        $builder->addTargets($interfaces);

                        continue;
                    }

                    if (strpos($type, 'alias:') === 0) {
                        $type = str_replace('alias:', '', $type);

                        $builder->addTarget('stdClass');
                        $builder->setName($type);

                        continue;
                    }

                    if (strpos($type, 'overload:') === 0) {
                        $type = str_replace('overload:', '', $type);

                        $builder->setInstanceMock(true);
                        $builder->addTarget('stdClass');
                        $builder->setName($type);

                        continue;
                    }

                    if ($type[strlen($type) - 1] === ']') {
                        $parts = explode('[', $type);

                        $class = $parts[0];

                        if (! class_exists($class, true) && ! interface_exists($class, true)) {
                            throw new Exception('Can only create a partial mock from an existing class or interface');
                        }

                        $builder->addTarget($class);

                        $partialMethods = array_filter(
                            explode(',', strtolower(rtrim(str_replace(' ', '', $parts[1]), ']')))
                        );

                        foreach ($partialMethods as $partialMethod) {
                            if ($partialMethod[0] === '!') {
                                $builder->addBlackListedMethod(substr($partialMethod, 1));

                                continue;
                            }

                            $builder->addWhiteListedMethod($partialMethod);
                        }

                        continue;
                    }

                    if (class_exists($type, true) || interface_exists($type, true) || trait_exists($type, true)) {
                        $builder->addTarget($type);

                        continue;
                    }

                    if (! $mockeryConfiguration->mockingNonExistentMethodsAllowed()) {
                        throw new Exception(sprintf("Mockery can't find '%s' so can't mock it", $type));
                    }

                    if (! $this->isValidClassName($type)) {
                        throw new Exception('Class name contains invalid characters');
                    }

                    $builder->addTarget($type);

                    // unions are "sum" types and not "intersections", and so we must only process the first part
                    break;
                }

                continue;
            }

            if (is_object($arg)) {
                $builder->addTarget($arg);

                continue;
            }

            if (is_array($arg)) {
                if ([] !== $arg && array_keys($arg) !== range(0, count($arg) - 1)) {
=======
                    } elseif (strpos($type, ',') && !strpos($type, ']')) {
                        $interfaces = explode(',', str_replace(' ', '', $type));
                        $builder->addTargets($interfaces);
                    } elseif (substr($type, 0, 6) == 'alias:') {
                        $type = str_replace('alias:', '', $type);
                        $builder->addTarget('stdClass');
                        $builder->setName($type);
                    } elseif (substr($type, 0, 9) == 'overload:') {
                        $type = str_replace('overload:', '', $type);
                        $builder->setInstanceMock(true);
                        $builder->addTarget('stdClass');
                        $builder->setName($type);
                    } elseif (substr($type, strlen($type)-1, 1) == ']') {
                        $parts = explode('[', $type);
                        if (!class_exists($parts[0], true) && !interface_exists($parts[0], true)) {
                            throw new \Mockery\Exception('Can only create a partial mock from'
                            . ' an existing class or interface');
                        }
                        $class = $parts[0];
                        $parts[1] = str_replace(' ', '', $parts[1]);
                        $partialMethods = array_filter(explode(',', strtolower(rtrim($parts[1], ']'))));
                        $builder->addTarget($class);
                        foreach ($partialMethods as $partialMethod) {
                            if ($partialMethod[0] === '!') {
                                $builder->addBlackListedMethod(substr($partialMethod, 1));
                                continue;
                            }
                            $builder->addWhiteListedMethod($partialMethod);
                        }
                    } elseif (class_exists($type, true) || interface_exists($type, true) || trait_exists($type, true)) {
                        $builder->addTarget($type);
                    } elseif (!\Mockery::getConfiguration()->mockingNonExistentMethodsAllowed() && (!class_exists($type, true) && !interface_exists($type, true))) {
                        throw new \Mockery\Exception("Mockery can't find '$type' so can't mock it");
                    } else {
                        if (!$this->isValidClassName($type)) {
                            throw new \Mockery\Exception('Class name contains invalid characters');
                        }
                        $builder->addTarget($type);
                    }
                    break; // unions are "sum" types and not "intersections", and so we must only process the first part
                }
            } elseif (is_object($arg)) {
                $builder->addTarget($arg);
            } elseif (is_array($arg)) {
                if (!empty($arg) && array_keys($arg) !== range(0, count($arg) - 1)) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                    // if associative array
                    if (array_key_exists(self::BLOCKS, $arg)) {
                        $blocks = $arg[self::BLOCKS];
                    }
<<<<<<< HEAD

                    unset($arg[self::BLOCKS]);

                    $quickDefinitions = $arg;

                    continue;
                }

                $constructorArgs = $arg;

                continue;
            }

            throw new Exception(sprintf(
                'Unable to parse arguments sent to %s::mock()', get_class($this)
            ));
=======
                    unset($arg[self::BLOCKS]);
                    $quickdefs = $arg;
                } else {
                    $constructorArgs = $arg;
                }
            } else {
                throw new \Mockery\Exception(
                    'Unable to parse arguments sent to '
                    . get_class($this) . '::mock()'
                );
            }
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        }

        $builder->addBlackListedMethods($blocks);

<<<<<<< HEAD
        if ($constructorArgs !== null) {
            $builder->addBlackListedMethod('__construct'); // we need to pass through
=======
        if (!is_null($constructorArgs)) {
            $builder->addBlackListedMethod("__construct"); // we need to pass through
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        } else {
            $builder->setMockOriginalDestructor(true);
        }

<<<<<<< HEAD
        if ($partialMethods !== null && $constructorArgs === null) {
            $constructorArgs = [];
=======
        if (!empty($partialMethods) && $constructorArgs === null) {
            $constructorArgs = array();
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        }

        $config = $builder->getMockConfiguration();

        $this->checkForNamedMockClashes($config);

        $def = $this->getGenerator()->generate($config);

<<<<<<< HEAD
        $className = $def->getClassName();
        if (class_exists($className, $attemptAutoload = false)) {
            $rfc = new ReflectionClass($className);
            if (! $rfc->implementsInterface(LegacyMockInterface::class)) {
                throw new RuntimeException(sprintf('Could not load mock %s, class already exists', $className));
=======
        if (class_exists($def->getClassName(), $attemptAutoload = false)) {
            $rfc = new \ReflectionClass($def->getClassName());
            if (!$rfc->implementsInterface("Mockery\LegacyMockInterface")) {
                throw new \Mockery\Exception\RuntimeException("Could not load mock {$def->getClassName()}, class already exists");
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            }
        }

        $this->getLoader()->load($def);

<<<<<<< HEAD
        $mock = $this->_getInstance($className, $constructorArgs);
        $mock->mockery_init($this, $config->getTargetObject(), $config->isInstanceMock());

        if ($quickDefinitions !== []) {
            if ($mockeryConfiguration->getQuickDefinitions()->shouldBeCalledAtLeastOnce()) {
                $mock->shouldReceive($quickDefinitions)->atLeast()->once();
            } else {
                $mock->shouldReceive($quickDefinitions)->byDefault();
            }
        }

        // if the last parameter passed to mock() is a closure,
        if ($expectationClosure instanceof Closure) {
            // call the closure with the mock object
            $expectationClosure($mock);
        }

        return $this->rememberMock($mock);
    }

    /**
     * Fetch the next available allocation order number
     *
     * @return int
     */
    public function mockery_allocateOrder()
    {
        return ++$this->_allocatedOrder;
    }

    /**
     * Reset the container to its original state
     *
     * @return void
     */
    public function mockery_close()
    {
        foreach ($this->_mocks as $mock) {
            $mock->mockery_teardown();
        }

        $this->_mocks = [];
    }

    /**
     * Get current ordered number
     *
     * @return int
     */
    public function mockery_getCurrentOrder()
    {
        return $this->_currentOrder;
    }

    /**
     * Gets the count of expectations on the mocks
     *
     * @return int
     */
    public function mockery_getExpectationCount()
    {
        $count = 0;
        foreach ($this->_mocks as $mock) {
            $count += $mock->mockery_getExpectationCount();
        }

        return $count;
    }

    /**
     * Fetch array of ordered groups
     *
     * @return array<string,int>
     */
    public function mockery_getGroups()
    {
        return $this->_groups;
    }

    /**
     * Set current ordered number
     *
     * @param int $order
     *
     * @return int The current order number that was set
     */
    public function mockery_setCurrentOrder($order)
    {
        return $this->_currentOrder = $order;
=======
        $mock = $this->_getInstance($def->getClassName(), $constructorArgs);
        $mock->mockery_init($this, $config->getTargetObject(), $config->isInstanceMock());

        if (!empty($quickdefs)) {
            if (\Mockery::getConfiguration()->getQuickDefinitions()->shouldBeCalledAtLeastOnce()) {
                $mock->shouldReceive($quickdefs)->atLeast()->once();
            } else {
                $mock->shouldReceive($quickdefs)->byDefault();
            }
        }
        if (!empty($expectationClosure)) {
            $expectationClosure($mock);
        }
        $this->rememberMock($mock);
        return $mock;
    }

    public function instanceMock()
    {
    }

    public function getLoader()
    {
        return $this->_loader;
    }

    public function getGenerator()
    {
        return $this->_generator;
    }

    /**
     * @param string $method
     * @param string $parent
     * @return string|null
     */
    public function getKeyOfDemeterMockFor($method, $parent)
    {
        $keys = array_keys($this->_mocks);
        $match = preg_grep("/__demeter_" . md5($parent) . "_{$method}$/", $keys);
        if (count($match) == 1) {
            $res = array_values($match);
            if (count($res) > 0) {
                return $res[0];
            }
        }
        return null;
    }

    /**
     * @return array
     */
    public function getMocks()
    {
        return $this->_mocks;
    }

    /**
     *  Tear down tasks for this container
     *
     * @throws \Exception
     * @return void
     */
    public function mockery_teardown()
    {
        try {
            $this->mockery_verify();
        } catch (\Exception $e) {
            $this->mockery_close();
            throw $e;
        }
    }

    /**
     * Verify the container mocks
     *
     * @return void
     */
    public function mockery_verify()
    {
        foreach ($this->_mocks as $mock) {
            $mock->mockery_verify();
        }
    }

    /**
     * Retrieves all exceptions thrown by mocks
     *
     * @return array
     */
    public function mockery_thrownExceptions()
    {
        $e = [];

        foreach ($this->_mocks as $mock) {
            $e = array_merge($e, $mock->mockery_thrownExceptions());
        }

        return $e;
    }

    /**
     * Reset the container to its original state
     *
     * @return void
     */
    public function mockery_close()
    {
        foreach ($this->_mocks as $mock) {
            $mock->mockery_teardown();
        }
        $this->_mocks = array();
    }

    /**
     * Fetch the next available allocation order number
     *
     * @return int
     */
    public function mockery_allocateOrder()
    {
        $this->_allocatedOrder += 1;
        return $this->_allocatedOrder;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * Set ordering for a group
     *
<<<<<<< HEAD
     * @param string $group
     * @param int    $order
     *
     * @return void
=======
     * @param mixed $group
     * @param int $order
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function mockery_setGroup($group, $order)
    {
        $this->_groups[$group] = $order;
    }

    /**
<<<<<<< HEAD
     * Tear down tasks for this container
     *
     * @throws PHPException
     */
    public function mockery_teardown()
    {
        try {
            $this->mockery_verify();
        } catch (PHPException $phpException) {
            $this->mockery_close();

            throw $phpException;
        }
    }

    /**
     * Retrieves all exceptions thrown by mocks
     *
     * @return array<Throwable>
     */
    public function mockery_thrownExceptions()
    {
        /** @var array<Throwable> $exceptions */
        $exceptions = [];

        foreach ($this->_mocks as $mock) {
            foreach ($mock->mockery_thrownExceptions() as $exception) {
                $exceptions[] = $exception;
            }
        }

        return $exceptions;
=======
     * Fetch array of ordered groups
     *
     * @return array
     */
    public function mockery_getGroups()
    {
        return $this->_groups;
    }

    /**
     * Set current ordered number
     *
     * @param int $order
     * @return int The current order number that was set
     */
    public function mockery_setCurrentOrder($order)
    {
        $this->_currentOrder = $order;
        return $this->_currentOrder;
    }

    /**
     * Get current ordered number
     *
     * @return int
     */
    public function mockery_getCurrentOrder()
    {
        return $this->_currentOrder;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * Validate the current mock's ordering
     *
     * @param string $method
<<<<<<< HEAD
     * @param int    $order
     *
     * @throws Exception
     */
    public function mockery_validateOrder($method, $order, LegacyMockInterface $mock)
    {
        if ($order < $this->_currentOrder) {
            $exception = new InvalidOrderException(
                sprintf(
                    'Method %s called out of order: expected order %d, was %d',
                    $method,
                    $order,
                    $this->_currentOrder
                )
            );

=======
     * @param int $order
     * @throws \Mockery\Exception
     * @return void
     */
    public function mockery_validateOrder($method, $order, \Mockery\LegacyMockInterface $mock)
    {
        if ($order < $this->_currentOrder) {
            $exception = new \Mockery\Exception\InvalidOrderException(
                'Method ' . $method . ' called out of order: expected order '
                . $order . ', was ' . $this->_currentOrder
            );
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            $exception->setMock($mock)
                ->setMethodName($method)
                ->setExpectedOrder($order)
                ->setActualOrder($this->_currentOrder);
<<<<<<< HEAD

            throw $exception;
        }

=======
            throw $exception;
        }
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $this->mockery_setCurrentOrder($order);
    }

    /**
<<<<<<< HEAD
     * Verify the container mocks
     */
    public function mockery_verify()
    {
        foreach ($this->_mocks as $mock) {
            $mock->mockery_verify();
        }
=======
     * Gets the count of expectations on the mocks
     *
     * @return int
     */
    public function mockery_getExpectationCount()
    {
        $count = 0;
        foreach ($this->_mocks as $mock) {
            $count += $mock->mockery_getExpectationCount();
        }
        return $count;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * Store a mock and set its container reference
     *
<<<<<<< HEAD
     * @template TRememberMock of object
     *
     * @param LegacyMockInterface&MockInterface&TRememberMock $mock
     *
     * @return LegacyMockInterface&MockInterface&TRememberMock
     */
    public function rememberMock(LegacyMockInterface $mock)
    {
        $class = get_class($mock);

        if (! array_key_exists($class, $this->_mocks)) {
            return $this->_mocks[$class] = $mock;
        }

        /**
         * This condition triggers for an instance mock where origin mock
         * is already remembered
         */
        return $this->_mocks[] = $mock;
    }

    /**
     * Retrieve the last remembered mock object,
     * which is the same as saying retrieve the current mock being programmed where you have yet to call mock()
     * to change it thus why the method name is "self" since it will be used during the programming of the same mock.
     *
     * @return LegacyMockInterface|MockInterface
=======
     * @param \Mockery\Mock $mock
     * @return \Mockery\LegacyMockInterface|\Mockery\MockInterface
     */
    public function rememberMock(\Mockery\LegacyMockInterface $mock)
    {
        if (!isset($this->_mocks[get_class($mock)])) {
            $this->_mocks[get_class($mock)] = $mock;
        } else {
            /**
             * This condition triggers for an instance mock where origin mock
             * is already remembered
             */
            $this->_mocks[] = $mock;
        }
        return $mock;
    }

    /**
     * Retrieve the last remembered mock object, which is the same as saying
     * retrieve the current mock being programmed where you have yet to call
     * mock() to change it - thus why the method name is "self" since it will be
     * be used during the programming of the same mock.
     *
     * @return \Mockery\Mock
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function self()
    {
        $mocks = array_values($this->_mocks);
        $index = count($mocks) - 1;
        return $mocks[$index];
    }

    /**
<<<<<<< HEAD
     * @template TMock of object
     * @template TMixed
     *
     * @param class-string<TMock> $mockName
     * @param null|array<TMixed>  $constructorArgs
     *
     * @return TMock
     */
    protected function _getInstance($mockName, $constructorArgs = null)
    {
        if ($constructorArgs !== null) {
            return (new ReflectionClass($mockName))->newInstanceArgs($constructorArgs);
        }

        try {
            $instance = $this->instantiator->instantiate($mockName);
        } catch (PHPException $phpException) {
            /** @var class-string<TMock> $internalMockName */
            $internalMockName = $mockName . '_Internal';

            if (! class_exists($internalMockName)) {
                eval(sprintf(
                    'class %s extends %s { public function __construct() {} }',
                    $internalMockName,
                    $mockName
                ));
=======
     * Return a specific remembered mock according to the array index it
     * was stored to in this container instance
     *
     * @return \Mockery\Mock
     */
    public function fetchMock($reference)
    {
        if (isset($this->_mocks[$reference])) {
            return $this->_mocks[$reference];
        }
    }

    protected function _getInstance($mockName, $constructorArgs = null)
    {
        if ($constructorArgs !== null) {
            $r = new \ReflectionClass($mockName);
            return $r->newInstanceArgs($constructorArgs);
        }

        try {
            $instantiator = new Instantiator();
            $instance = $instantiator->instantiate($mockName);
        } catch (\Exception $ex) {
            $internalMockName = $mockName . '_Internal';

            if (!class_exists($internalMockName)) {
                eval("class $internalMockName extends $mockName {" .
                        'public function __construct() {}' .
                    '}');
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            }

            $instance = new $internalMockName();
        }

        return $instance;
    }

    protected function checkForNamedMockClashes($config)
    {
        $name = $config->getName();

<<<<<<< HEAD
        if ($name === null) {
=======
        if (!$name) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            return;
        }

        $hash = $config->getHash();

<<<<<<< HEAD
        if (array_key_exists($name, $this->_namedMocks) && $hash !== $this->_namedMocks[$name]) {
            throw new Exception(
                sprintf("The mock named '%s' has been already defined with a different mock configuration", $name)
            );
=======
        if (isset($this->_namedMocks[$name])) {
            if ($hash !== $this->_namedMocks[$name]) {
                throw new \Mockery\Exception(
                    "The mock named '$name' has been already defined with a different mock configuration"
                );
            }
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        }

        $this->_namedMocks[$name] = $hash;
    }
<<<<<<< HEAD
=======

    /**
     * see http://php.net/manual/en/language.oop5.basic.php
     * @param string $className
     * @return bool
     */
    public function isValidClassName($className)
    {
        $pos = strpos($className, '\\');
        if ($pos === 0) {
            $className = substr($className, 1); // remove the first backslash
        }
        // all the namespaces and class name should match the regex
        $invalidNames = array_filter(explode('\\', $className), function ($name) {
            return !preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $name);
        });
        return empty($invalidNames);
    }
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
}
