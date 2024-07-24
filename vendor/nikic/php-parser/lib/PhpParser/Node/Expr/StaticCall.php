<?php declare(strict_types=1);

namespace PhpParser\Node\Expr;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Identifier;
use PhpParser\Node\VariadicPlaceholder;

<<<<<<< HEAD
class StaticCall extends CallLike {
    /** @var Node\Name|Expr Class name */
    public Node $class;
    /** @var Identifier|Expr Method name */
    public Node $name;
    /** @var array<Arg|VariadicPlaceholder> Arguments */
    public array $args;
=======
class StaticCall extends CallLike
{
    /** @var Node\Name|Expr Class name */
    public $class;
    /** @var Identifier|Expr Method name */
    public $name;
    /** @var array<Arg|VariadicPlaceholder> Arguments */
    public $args;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Constructs a static method call node.
     *
<<<<<<< HEAD
     * @param Node\Name|Expr $class Class name
     * @param string|Identifier|Expr $name Method name
     * @param array<Arg|VariadicPlaceholder> $args Arguments
     * @param array<string, mixed> $attributes Additional attributes
     */
    public function __construct(Node $class, $name, array $args = [], array $attributes = []) {
=======
     * @param Node\Name|Expr                 $class      Class name
     * @param string|Identifier|Expr         $name       Method name
     * @param array<Arg|VariadicPlaceholder> $args       Arguments
     * @param array                          $attributes Additional attributes
     */
    public function __construct($class, $name, array $args = [], array $attributes = []) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $this->attributes = $attributes;
        $this->class = $class;
        $this->name = \is_string($name) ? new Identifier($name) : $name;
        $this->args = $args;
    }

<<<<<<< HEAD
    public function getSubNodeNames(): array {
        return ['class', 'name', 'args'];
    }

    public function getType(): string {
=======
    public function getSubNodeNames() : array {
        return ['class', 'name', 'args'];
    }
    
    public function getType() : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return 'Expr_StaticCall';
    }

    public function getRawArgs(): array {
        return $this->args;
    }
}
