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

namespace Mockery\Loader;

use Mockery\Generator\MockDefinition;
<<<<<<< HEAD

use function array_diff;
use function class_exists;
use function file_exists;
use function file_put_contents;
use function glob;
use function realpath;
use function sprintf;
use function sys_get_temp_dir;
use function uniqid;
use function unlink;

use const DIRECTORY_SEPARATOR;
=======
use Mockery\Loader\Loader;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

class RequireLoader implements Loader
{
    /**
     * @var string
     */
<<<<<<< HEAD
    protected $lastPath = '';
=======
    protected $path;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * @var string
     */
<<<<<<< HEAD
    protected $path;

    /**
     * @param string|null $path
     */
    public function __construct($path = null)
    {
        if ($path === null) {
            $path = sys_get_temp_dir();
        }

        $this->path = realpath($path);
=======
    protected $lastPath = '';

    public function __construct($path = null)
    {
        $this->path = realpath($path) ?: sys_get_temp_dir();

        register_shutdown_function([$this, '__destruct']);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    public function __destruct()
    {
<<<<<<< HEAD
        $files = array_diff(glob($this->path . DIRECTORY_SEPARATOR . 'Mockery_*.php') ?: [], [$this->lastPath]);
=======
        $files = array_diff(
            glob($this->path . DIRECTORY_SEPARATOR . 'Mockery_*.php')?:[],
            [$this->lastPath]
        );
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

        foreach ($files as $file) {
            @unlink($file);
        }
    }

<<<<<<< HEAD
    /**
     * Load the given mock definition
     *
     * @return void
     */
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function load(MockDefinition $definition)
    {
        if (class_exists($definition->getClassName(), false)) {
            return;
        }

<<<<<<< HEAD
        $this->lastPath = sprintf('%s%s%s.php', $this->path, DIRECTORY_SEPARATOR, uniqid('Mockery_', false));

        file_put_contents($this->lastPath, $definition->getCode());

        if (file_exists($this->lastPath)) {
=======
        $this->lastPath = sprintf('%s%s%s.php', $this->path, DIRECTORY_SEPARATOR, uniqid('Mockery_'));

        file_put_contents($this->lastPath, $definition->getCode());

        if (file_exists($this->lastPath)){
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            require $this->lastPath;
        }
    }
}
