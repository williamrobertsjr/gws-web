<?php declare(strict_types=1);

namespace PhpParser\Builder;

use PhpParser\Builder;
use PhpParser\BuilderHelpers;
<<<<<<< HEAD
use PhpParser\Modifiers;
use PhpParser\Node;
use PhpParser\Node\Stmt;

class TraitUseAdaptation implements Builder {
    private const TYPE_UNDEFINED  = 0;
    private const TYPE_ALIAS      = 1;
    private const TYPE_PRECEDENCE = 2;

    protected int $type;
    protected ?Node\Name $trait;
    protected Node\Identifier $method;
    protected ?int $modifier = null;
    protected ?Node\Identifier $alias = null;
    /** @var Node\Name[] */
    protected array $insteadof = [];
=======
use PhpParser\Node;
use PhpParser\Node\Stmt;

class TraitUseAdaptation implements Builder
{
    const TYPE_UNDEFINED  = 0;
    const TYPE_ALIAS      = 1;
    const TYPE_PRECEDENCE = 2;

    /** @var int Type of building adaptation */
    protected $type;

    protected $trait;
    protected $method;

    protected $modifier = null;
    protected $alias = null;

    protected $insteadof = [];
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Creates a trait use adaptation builder.
     *
<<<<<<< HEAD
     * @param Node\Name|string|null $trait Name of adapted trait
     * @param Node\Identifier|string $method Name of adapted method
=======
     * @param Node\Name|string|null  $trait  Name of adaptated trait
     * @param Node\Identifier|string $method Name of adaptated method
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct($trait, $method) {
        $this->type = self::TYPE_UNDEFINED;

<<<<<<< HEAD
        $this->trait = is_null($trait) ? null : BuilderHelpers::normalizeName($trait);
=======
        $this->trait = is_null($trait)? null: BuilderHelpers::normalizeName($trait);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $this->method = BuilderHelpers::normalizeIdentifier($method);
    }

    /**
     * Sets alias of method.
     *
<<<<<<< HEAD
     * @param Node\Identifier|string $alias Alias for adapted method
=======
     * @param Node\Identifier|string $alias Alias for adaptated method
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function as($alias) {
        if ($this->type === self::TYPE_UNDEFINED) {
            $this->type = self::TYPE_ALIAS;
        }

        if ($this->type !== self::TYPE_ALIAS) {
            throw new \LogicException('Cannot set alias for not alias adaptation buider');
        }

<<<<<<< HEAD
        $this->alias = BuilderHelpers::normalizeIdentifier($alias);
=======
        $this->alias = $alias;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this;
    }

    /**
<<<<<<< HEAD
     * Sets adapted method public.
=======
     * Sets adaptated method public.
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function makePublic() {
<<<<<<< HEAD
        $this->setModifier(Modifiers::PUBLIC);
=======
        $this->setModifier(Stmt\Class_::MODIFIER_PUBLIC);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this;
    }

    /**
<<<<<<< HEAD
     * Sets adapted method protected.
=======
     * Sets adaptated method protected.
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function makeProtected() {
<<<<<<< HEAD
        $this->setModifier(Modifiers::PROTECTED);
=======
        $this->setModifier(Stmt\Class_::MODIFIER_PROTECTED);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this;
    }

    /**
<<<<<<< HEAD
     * Sets adapted method private.
=======
     * Sets adaptated method private.
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function makePrivate() {
<<<<<<< HEAD
        $this->setModifier(Modifiers::PRIVATE);
=======
        $this->setModifier(Stmt\Class_::MODIFIER_PRIVATE);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this;
    }

    /**
     * Adds overwritten traits.
     *
     * @param Node\Name|string ...$traits Traits for overwrite
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function insteadof(...$traits) {
        if ($this->type === self::TYPE_UNDEFINED) {
            if (is_null($this->trait)) {
                throw new \LogicException('Precedence adaptation must have trait');
            }

            $this->type = self::TYPE_PRECEDENCE;
        }

        if ($this->type !== self::TYPE_PRECEDENCE) {
            throw new \LogicException('Cannot add overwritten traits for not precedence adaptation buider');
        }

        foreach ($traits as $trait) {
            $this->insteadof[] = BuilderHelpers::normalizeName($trait);
        }

        return $this;
    }

<<<<<<< HEAD
    protected function setModifier(int $modifier): void {
=======
    protected function setModifier(int $modifier) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        if ($this->type === self::TYPE_UNDEFINED) {
            $this->type = self::TYPE_ALIAS;
        }

        if ($this->type !== self::TYPE_ALIAS) {
            throw new \LogicException('Cannot set access modifier for not alias adaptation buider');
        }

        if (is_null($this->modifier)) {
            $this->modifier = $modifier;
        } else {
            throw new \LogicException('Multiple access type modifiers are not allowed');
        }
    }

    /**
     * Returns the built node.
     *
     * @return Node The built node
     */
<<<<<<< HEAD
    public function getNode(): Node {
=======
    public function getNode() : Node {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        switch ($this->type) {
            case self::TYPE_ALIAS:
                return new Stmt\TraitUseAdaptation\Alias($this->trait, $this->method, $this->modifier, $this->alias);
            case self::TYPE_PRECEDENCE:
                return new Stmt\TraitUseAdaptation\Precedence($this->trait, $this->method, $this->insteadof);
            default:
                throw new \LogicException('Type of adaptation is not defined');
        }
    }
}
