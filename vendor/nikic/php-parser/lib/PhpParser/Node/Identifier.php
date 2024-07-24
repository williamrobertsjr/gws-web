<?php declare(strict_types=1);

namespace PhpParser\Node;

use PhpParser\NodeAbstract;

/**
 * Represents a non-namespaced name. Namespaced names are represented using Name nodes.
 */
<<<<<<< HEAD
class Identifier extends NodeAbstract {
    /**
     * @psalm-var non-empty-string
     * @var string Identifier as string
     */
    public string $name;

    /** @var array<string, bool> */
    private static array $specialClassNames = [
=======
class Identifier extends NodeAbstract
{
    /** @var string Identifier as string */
    public $name;

    private static $specialClassNames = [
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        'self'   => true,
        'parent' => true,
        'static' => true,
    ];

    /**
     * Constructs an identifier node.
     *
<<<<<<< HEAD
     * @param string $name Identifier as string
     * @param array<string, mixed> $attributes Additional attributes
     */
    public function __construct(string $name, array $attributes = []) {
        if ($name === '') {
            throw new \InvalidArgumentException('Identifier name cannot be empty');
        }

=======
     * @param string $name       Identifier as string
     * @param array  $attributes Additional attributes
     */
    public function __construct(string $name, array $attributes = []) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $this->attributes = $attributes;
        $this->name = $name;
    }

<<<<<<< HEAD
    public function getSubNodeNames(): array {
=======
    public function getSubNodeNames() : array {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return ['name'];
    }

    /**
     * Get identifier as string.
     *
<<<<<<< HEAD
     * @psalm-return non-empty-string
     * @return string Identifier as string.
     */
    public function toString(): string {
=======
     * @return string Identifier as string.
     */
    public function toString() : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this->name;
    }

    /**
     * Get lowercased identifier as string.
     *
<<<<<<< HEAD
     * @psalm-return non-empty-string
     * @return string Lowercased identifier as string
     */
    public function toLowerString(): string {
=======
     * @return string Lowercased identifier as string
     */
    public function toLowerString() : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return strtolower($this->name);
    }

    /**
     * Checks whether the identifier is a special class name (self, parent or static).
     *
     * @return bool Whether identifier is a special class name
     */
<<<<<<< HEAD
    public function isSpecialClassName(): bool {
=======
    public function isSpecialClassName() : bool {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return isset(self::$specialClassNames[strtolower($this->name)]);
    }

    /**
     * Get identifier as string.
     *
<<<<<<< HEAD
     * @psalm-return non-empty-string
     * @return string Identifier as string
     */
    public function __toString(): string {
        return $this->name;
    }

    public function getType(): string {
=======
     * @return string Identifier as string
     */
    public function __toString() : string {
        return $this->name;
    }
    
    public function getType() : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return 'Identifier';
    }
}
