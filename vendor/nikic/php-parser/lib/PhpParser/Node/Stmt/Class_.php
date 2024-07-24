<?php declare(strict_types=1);

namespace PhpParser\Node\Stmt;

<<<<<<< HEAD
use PhpParser\Modifiers;
use PhpParser\Node;

class Class_ extends ClassLike {
    /** @deprecated Use Modifiers::PUBLIC instead */
    public const MODIFIER_PUBLIC    =  1;
    /** @deprecated Use Modifiers::PROTECTED instead */
    public const MODIFIER_PROTECTED =  2;
    /** @deprecated Use Modifiers::PRIVATE instead */
    public const MODIFIER_PRIVATE   =  4;
    /** @deprecated Use Modifiers::STATIC instead */
    public const MODIFIER_STATIC    =  8;
    /** @deprecated Use Modifiers::ABSTRACT instead */
    public const MODIFIER_ABSTRACT  = 16;
    /** @deprecated Use Modifiers::FINAL instead */
    public const MODIFIER_FINAL     = 32;
    /** @deprecated Use Modifiers::READONLY instead */
    public const MODIFIER_READONLY  = 64;

    /** @deprecated Use Modifiers::VISIBILITY_MASK instead */
    public const VISIBILITY_MODIFIER_MASK = 7; // 1 | 2 | 4

    /** @var int Modifiers */
    public int $flags;
    /** @var null|Node\Name Name of extended class */
    public ?Node\Name $extends;
    /** @var Node\Name[] Names of implemented interfaces */
    public array $implements;
=======
use PhpParser\Error;
use PhpParser\Node;

class Class_ extends ClassLike
{
    const MODIFIER_PUBLIC    =  1;
    const MODIFIER_PROTECTED =  2;
    const MODIFIER_PRIVATE   =  4;
    const MODIFIER_STATIC    =  8;
    const MODIFIER_ABSTRACT  = 16;
    const MODIFIER_FINAL     = 32;
    const MODIFIER_READONLY  = 64;

    const VISIBILITY_MODIFIER_MASK = 7; // 1 | 2 | 4

    /** @var int Type */
    public $flags;
    /** @var null|Node\Name Name of extended class */
    public $extends;
    /** @var Node\Name[] Names of implemented interfaces */
    public $implements;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Constructs a class node.
     *
     * @param string|Node\Identifier|null $name Name
<<<<<<< HEAD
     * @param array{
     *     flags?: int,
     *     extends?: Node\Name|null,
     *     implements?: Node\Name[],
     *     stmts?: Node\Stmt[],
     *     attrGroups?: Node\AttributeGroup[],
     * } $subNodes Array of the following optional subnodes:
     *             'flags'       => 0      : Flags
     *             'extends'     => null   : Name of extended class
     *             'implements'  => array(): Names of implemented interfaces
     *             'stmts'       => array(): Statements
     *             'attrGroups'  => array(): PHP attribute groups
     * @param array<string, mixed> $attributes Additional attributes
=======
     * @param array       $subNodes   Array of the following optional subnodes:
     *                                'flags'       => 0      : Flags
     *                                'extends'     => null   : Name of extended class
     *                                'implements'  => array(): Names of implemented interfaces
     *                                'stmts'       => array(): Statements
     *                                'attrGroups'  => array(): PHP attribute groups
     * @param array       $attributes Additional attributes
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct($name, array $subNodes = [], array $attributes = []) {
        $this->attributes = $attributes;
        $this->flags = $subNodes['flags'] ?? $subNodes['type'] ?? 0;
        $this->name = \is_string($name) ? new Node\Identifier($name) : $name;
        $this->extends = $subNodes['extends'] ?? null;
        $this->implements = $subNodes['implements'] ?? [];
        $this->stmts = $subNodes['stmts'] ?? [];
        $this->attrGroups = $subNodes['attrGroups'] ?? [];
    }

<<<<<<< HEAD
    public function getSubNodeNames(): array {
=======
    public function getSubNodeNames() : array {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return ['attrGroups', 'flags', 'name', 'extends', 'implements', 'stmts'];
    }

    /**
     * Whether the class is explicitly abstract.
<<<<<<< HEAD
     */
    public function isAbstract(): bool {
        return (bool) ($this->flags & Modifiers::ABSTRACT);
=======
     *
     * @return bool
     */
    public function isAbstract() : bool {
        return (bool) ($this->flags & self::MODIFIER_ABSTRACT);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * Whether the class is final.
<<<<<<< HEAD
     */
    public function isFinal(): bool {
        return (bool) ($this->flags & Modifiers::FINAL);
    }

    public function isReadonly(): bool {
        return (bool) ($this->flags & Modifiers::READONLY);
=======
     *
     * @return bool
     */
    public function isFinal() : bool {
        return (bool) ($this->flags & self::MODIFIER_FINAL);
    }

    public function isReadonly() : bool {
        return (bool) ($this->flags & self::MODIFIER_READONLY);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * Whether the class is anonymous.
<<<<<<< HEAD
     */
    public function isAnonymous(): bool {
        return null === $this->name;
    }

    public function getType(): string {
=======
     *
     * @return bool
     */
    public function isAnonymous() : bool {
        return null === $this->name;
    }

    /**
     * @internal
     */
    public static function verifyClassModifier($a, $b) {
        if ($a & self::MODIFIER_ABSTRACT && $b & self::MODIFIER_ABSTRACT) {
            throw new Error('Multiple abstract modifiers are not allowed');
        }

        if ($a & self::MODIFIER_FINAL && $b & self::MODIFIER_FINAL) {
            throw new Error('Multiple final modifiers are not allowed');
        }

        if ($a & self::MODIFIER_READONLY && $b & self::MODIFIER_READONLY) {
            throw new Error('Multiple readonly modifiers are not allowed');
        }

        if ($a & 48 && $b & 48) {
            throw new Error('Cannot use the final modifier on an abstract class');
        }
    }

    /**
     * @internal
     */
    public static function verifyModifier($a, $b) {
        if ($a & self::VISIBILITY_MODIFIER_MASK && $b & self::VISIBILITY_MODIFIER_MASK) {
            throw new Error('Multiple access type modifiers are not allowed');
        }

        if ($a & self::MODIFIER_ABSTRACT && $b & self::MODIFIER_ABSTRACT) {
            throw new Error('Multiple abstract modifiers are not allowed');
        }

        if ($a & self::MODIFIER_STATIC && $b & self::MODIFIER_STATIC) {
            throw new Error('Multiple static modifiers are not allowed');
        }

        if ($a & self::MODIFIER_FINAL && $b & self::MODIFIER_FINAL) {
            throw new Error('Multiple final modifiers are not allowed');
        }

        if ($a & self::MODIFIER_READONLY && $b & self::MODIFIER_READONLY) {
            throw new Error('Multiple readonly modifiers are not allowed');
        }

        if ($a & 48 && $b & 48) {
            throw new Error('Cannot use the final modifier on an abstract class member');
        }
    }

    public function getType() : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return 'Stmt_Class';
    }
}
