<?php declare(strict_types=1);

namespace PhpParser\Node\Stmt;

<<<<<<< HEAD
use PhpParser\Modifiers;
use PhpParser\Node;

class ClassConst extends Node\Stmt {
    /** @var int Modifiers */
    public int $flags;
    /** @var Node\Const_[] Constant declarations */
    public array $consts;
    /** @var Node\AttributeGroup[] PHP attribute groups */
    public array $attrGroups;
    /** @var Node\Identifier|Node\Name|Node\ComplexType|null Type declaration */
    public ?Node $type;
=======
use PhpParser\Node;

class ClassConst extends Node\Stmt
{
    /** @var int Modifiers */
    public $flags;
    /** @var Node\Const_[] Constant declarations */
    public $consts;
    /** @var Node\AttributeGroup[] PHP attribute groups */
    public $attrGroups;
    /** @var Node\Identifier|Node\Name|Node\ComplexType|null Type declaration */
    public $type;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Constructs a class const list node.
     *
<<<<<<< HEAD
     * @param Node\Const_[] $consts Constant declarations
     * @param int $flags Modifiers
     * @param array<string, mixed> $attributes Additional attributes
     * @param list<Node\AttributeGroup> $attrGroups PHP attribute groups
     * @param null|Node\Identifier|Node\Name|Node\ComplexType $type Type declaration
=======
     * @param Node\Const_[]                                          $consts     Constant declarations
     * @param int                                                    $flags      Modifiers
     * @param array                                                  $attributes Additional attributes
     * @param Node\AttributeGroup[]                                  $attrGroups PHP attribute groups
     * @param null|string|Node\Identifier|Node\Name|Node\ComplexType $type       Type declaration
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct(
        array $consts,
        int $flags = 0,
        array $attributes = [],
        array $attrGroups = [],
<<<<<<< HEAD
        ?Node $type = null
=======
        $type = null
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    ) {
        $this->attributes = $attributes;
        $this->flags = $flags;
        $this->consts = $consts;
        $this->attrGroups = $attrGroups;
<<<<<<< HEAD
        $this->type = $type;
    }

    public function getSubNodeNames(): array {
=======
        $this->type = \is_string($type) ? new Node\Identifier($type) : $type;
    }

    public function getSubNodeNames() : array {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return ['attrGroups', 'flags', 'type', 'consts'];
    }

    /**
     * Whether constant is explicitly or implicitly public.
<<<<<<< HEAD
     */
    public function isPublic(): bool {
        return ($this->flags & Modifiers::PUBLIC) !== 0
            || ($this->flags & Modifiers::VISIBILITY_MASK) === 0;
=======
     *
     * @return bool
     */
    public function isPublic() : bool {
        return ($this->flags & Class_::MODIFIER_PUBLIC) !== 0
            || ($this->flags & Class_::VISIBILITY_MODIFIER_MASK) === 0;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * Whether constant is protected.
<<<<<<< HEAD
     */
    public function isProtected(): bool {
        return (bool) ($this->flags & Modifiers::PROTECTED);
=======
     *
     * @return bool
     */
    public function isProtected() : bool {
        return (bool) ($this->flags & Class_::MODIFIER_PROTECTED);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * Whether constant is private.
<<<<<<< HEAD
     */
    public function isPrivate(): bool {
        return (bool) ($this->flags & Modifiers::PRIVATE);
=======
     *
     * @return bool
     */
    public function isPrivate() : bool {
        return (bool) ($this->flags & Class_::MODIFIER_PRIVATE);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * Whether constant is final.
<<<<<<< HEAD
     */
    public function isFinal(): bool {
        return (bool) ($this->flags & Modifiers::FINAL);
    }

    public function getType(): string {
=======
     *
     * @return bool
     */
    public function isFinal() : bool {
        return (bool) ($this->flags & Class_::MODIFIER_FINAL);
    }

    public function getType() : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return 'Stmt_ClassConst';
    }
}
