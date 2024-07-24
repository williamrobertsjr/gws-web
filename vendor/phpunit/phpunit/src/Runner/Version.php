<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Runner;

use function array_slice;
<<<<<<< HEAD
use function assert;
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
use function dirname;
use function explode;
use function implode;
use function strpos;
use SebastianBergmann\Version as VersionId;

/**
 * @no-named-arguments Parameter names are not covered by the backward compatibility promise for PHPUnit
 */
final class Version
{
    /**
     * @var string
     */
    private static $pharVersion = '';

    /**
     * @var string
     */
    private static $version = '';

    /**
     * Returns the current version of PHPUnit.
<<<<<<< HEAD
     *
     * @psalm-return non-empty-string
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public static function id(): string
    {
        if (self::$pharVersion !== '') {
            return self::$pharVersion;
        }

        if (self::$version === '') {
<<<<<<< HEAD
            self::$version = (new VersionId('9.6.20', dirname(__DIR__, 2)))->getVersion();

            assert(!empty(self::$version));
=======
            self::$version = (new VersionId('9.6.15', dirname(__DIR__, 2)))->getVersion();
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        }

        return self::$version;
    }

<<<<<<< HEAD
    /**
     * @psalm-return non-empty-string
     */
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public static function series(): string
    {
        if (strpos(self::id(), '-')) {
            $version = explode('-', self::id())[0];
        } else {
            $version = self::id();
        }

        return implode('.', array_slice(explode('.', $version), 0, 2));
    }

<<<<<<< HEAD
    /**
     * @psalm-return non-empty-string
     */
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public static function getVersionString(): string
    {
        return 'PHPUnit ' . self::id() . ' by Sebastian Bergmann and contributors.';
    }
}
