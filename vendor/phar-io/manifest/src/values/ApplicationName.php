<?php declare(strict_types = 1);
/*
 * This file is part of PharIo\Manifest.
 *
<<<<<<< HEAD
 * Copyright (c) Arne Blankerts <arne@blankerts.de>, Sebastian Heuer <sebastian@phpeople.de>, Sebastian Bergmann <sebastian@phpunit.de> and contributors
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace PharIo\Manifest;

use function preg_match;
use function sprintf;

=======
 * (c) Arne Blankerts <arne@blankerts.de>, Sebastian Heuer <sebastian@phpeople.de>, Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PharIo\Manifest;

>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
class ApplicationName {
    /** @var string */
    private $name;

    public function __construct(string $name) {
        $this->ensureValidFormat($name);
        $this->name = $name;
    }

    public function asString(): string {
        return $this->name;
    }

    public function isEqual(ApplicationName $name): bool {
        return $this->name === $name->name;
    }

    private function ensureValidFormat(string $name): void {
<<<<<<< HEAD
        if (!preg_match('#\w/\w#', $name)) {
            throw new InvalidApplicationNameException(
                sprintf('Format of name "%s" is not valid - expected: vendor/packagename', $name),
=======
        if (!\preg_match('#\w/\w#', $name)) {
            throw new InvalidApplicationNameException(
                \sprintf('Format of name "%s" is not valid - expected: vendor/packagename', $name),
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                InvalidApplicationNameException::InvalidFormat
            );
        }
    }
}
