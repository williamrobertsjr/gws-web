<?php declare(strict_types=1);

namespace PhpParser\Builder;

use PhpParser;
use PhpParser\BuilderHelpers;
<<<<<<< HEAD
use PhpParser\Modifiers;
use PhpParser\Node;

class Param implements PhpParser\Builder {
    protected string $name;
    protected ?Node\Expr $default = null;
    /** @var Node\Identifier|Node\Name|Node\ComplexType|null */
    protected ?Node $type = null;
    protected bool $byRef = false;
    protected int $flags = 0;
    protected bool $variadic = false;
    /** @var list<Node\AttributeGroup> */
    protected array $attributeGroups = [];
=======
use PhpParser\Node;

class Param implements PhpParser\Builder
{
    protected $name;

    protected $default = null;

    /** @var Node\Identifier|Node\Name|Node\NullableType|null */
    protected $type = null;

    protected $byRef = false;

    protected $variadic = false;

    protected $flags = 0;

    /** @var Node\AttributeGroup[] */
    protected $attributeGroups = [];
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Creates a parameter builder.
     *
     * @param string $name Name of the parameter
     */
    public function __construct(string $name) {
        $this->name = $name;
    }

    /**
     * Sets default value for the parameter.
     *
     * @param mixed $value Default value to use
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function setDefault($value) {
        $this->default = BuilderHelpers::normalizeValue($value);

        return $this;
    }

    /**
     * Sets type for the parameter.
     *
     * @param string|Node\Name|Node\Identifier|Node\ComplexType $type Parameter type
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function setType($type) {
        $this->type = BuilderHelpers::normalizeType($type);
        if ($this->type == 'void') {
            throw new \LogicException('Parameter type cannot be void');
        }

        return $this;
    }

    /**
<<<<<<< HEAD
=======
     * Sets type for the parameter.
     *
     * @param string|Node\Name|Node\Identifier|Node\ComplexType $type Parameter type
     *
     * @return $this The builder instance (for fluid interface)
     *
     * @deprecated Use setType() instead
     */
    public function setTypeHint($type) {
        return $this->setType($type);
    }

    /**
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     * Make the parameter accept the value by reference.
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function makeByRef() {
        $this->byRef = true;

        return $this;
    }

    /**
     * Make the parameter variadic
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function makeVariadic() {
        $this->variadic = true;

        return $this;
    }

    /**
     * Makes the (promoted) parameter public.
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function makePublic() {
<<<<<<< HEAD
        $this->flags = BuilderHelpers::addModifier($this->flags, Modifiers::PUBLIC);
=======
        $this->flags = BuilderHelpers::addModifier($this->flags, Node\Stmt\Class_::MODIFIER_PUBLIC);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

        return $this;
    }

    /**
     * Makes the (promoted) parameter protected.
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function makeProtected() {
<<<<<<< HEAD
        $this->flags = BuilderHelpers::addModifier($this->flags, Modifiers::PROTECTED);
=======
        $this->flags = BuilderHelpers::addModifier($this->flags, Node\Stmt\Class_::MODIFIER_PROTECTED);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

        return $this;
    }

    /**
     * Makes the (promoted) parameter private.
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function makePrivate() {
<<<<<<< HEAD
        $this->flags = BuilderHelpers::addModifier($this->flags, Modifiers::PRIVATE);
=======
        $this->flags = BuilderHelpers::addModifier($this->flags, Node\Stmt\Class_::MODIFIER_PRIVATE);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

        return $this;
    }

    /**
     * Makes the (promoted) parameter readonly.
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function makeReadonly() {
<<<<<<< HEAD
        $this->flags = BuilderHelpers::addModifier($this->flags, Modifiers::READONLY);
=======
        $this->flags = BuilderHelpers::addModifier($this->flags, Node\Stmt\Class_::MODIFIER_READONLY);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

        return $this;
    }

    /**
     * Adds an attribute group.
     *
     * @param Node\Attribute|Node\AttributeGroup $attribute
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function addAttribute($attribute) {
        $this->attributeGroups[] = BuilderHelpers::normalizeAttribute($attribute);

        return $this;
    }

    /**
     * Returns the built parameter node.
     *
     * @return Node\Param The built parameter node
     */
<<<<<<< HEAD
    public function getNode(): Node {
=======
    public function getNode() : Node {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Node\Param(
            new Node\Expr\Variable($this->name),
            $this->default, $this->type, $this->byRef, $this->variadic, [], $this->flags, $this->attributeGroups
        );
    }
}
