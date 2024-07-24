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

namespace Mockery\Generator;

<<<<<<< HEAD
use InvalidArgumentException;

class MockDefinition
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var MockConfiguration
     */
    protected $config;

    /**
     * @param  string                   $code
     * @throws InvalidArgumentException
     */
    public function __construct(MockConfiguration $config, $code)
    {
        if (! $config->getName()) {
            throw new InvalidArgumentException('MockConfiguration must contain a name');
        }

=======
class MockDefinition
{
    protected $config;
    protected $code;

    public function __construct(MockConfiguration $config, $code)
    {
        if (!$config->getName()) {
            throw new \InvalidArgumentException("MockConfiguration must contain a name");
        }
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $this->config = $config;
        $this->code = $code;
    }

<<<<<<< HEAD
    /**
     * @return string
     */
=======
    public function getConfig()
    {
        return $this->config;
    }

>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function getClassName()
    {
        return $this->config->getName();
    }

<<<<<<< HEAD
    /**
     * @return string
     */
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function getCode()
    {
        return $this->code;
    }
<<<<<<< HEAD

    /**
     * @return MockConfiguration
     */
    public function getConfig()
    {
        return $this->config;
    }
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
}
