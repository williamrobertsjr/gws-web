<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\TextUI\XmlConfiguration;

<<<<<<< HEAD
use PHPUnit\Exception;
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
use RuntimeException;

/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 */
<<<<<<< HEAD
final class MigrationBuilderException extends RuntimeException implements Exception
=======
final class MigrationBuilderException extends RuntimeException implements \PHPUnit\Exception
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
{
}
