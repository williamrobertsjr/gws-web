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

class CachingGenerator implements Generator
{
<<<<<<< HEAD
    /**
     * @var array<string,string>
     */
    protected $cache = [];

    /**
     * @var Generator
     */
    protected $generator;
=======
    protected $generator;
    protected $cache = array();
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

<<<<<<< HEAD
    /**
     * @return string
     */
    public function generate(MockConfiguration $config)
    {
        $hash = $config->getHash();

        if (array_key_exists($hash, $this->cache)) {
            return $this->cache[$hash];
        }

        return $this->cache[$hash] = $this->generator->generate($config);
=======
    public function generate(MockConfiguration $config)
    {
        $hash = $config->getHash();
        if (isset($this->cache[$hash])) {
            return $this->cache[$hash];
        }

        $definition = $this->generator->generate($config);
        $this->cache[$hash] = $definition;

        return $definition;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }
}
