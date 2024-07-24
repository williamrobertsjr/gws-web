<?php declare(strict_types=1);
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PHPUnit\Util\Xml;

/**
 * @internal This class is not covered by the backward compatibility promise for PHPUnit
 *
 * @psalm-immutable
 */
final class SuccessfulSchemaDetectionResult extends SchemaDetectionResult
{
    /**
<<<<<<< HEAD
     * @psalm-var non-empty-string
     */
    private $version;

    /**
     * @psalm-param non-empty-string $version
     */
=======
     * @var string
     */
    private $version;

>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function __construct(string $version)
    {
        $this->version = $version;
    }

<<<<<<< HEAD
    /**
     * @psalm-assert-if-true SuccessfulSchemaDetectionResult $this
     */
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function detected(): bool
    {
        return true;
    }

<<<<<<< HEAD
    /**
     * @psalm-return non-empty-string
     */
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function version(): string
    {
        return $this->version;
    }
}
