<?php declare(strict_types=1);

namespace PhpParser\Node\Expr;

use PhpParser\Node\Expr;

<<<<<<< HEAD
class Variable extends Expr {
=======
class Variable extends Expr
{
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    /** @var string|Expr Name */
    public $name;

    /**
     * Constructs a variable node.
     *
<<<<<<< HEAD
     * @param string|Expr $name Name
     * @param array<string, mixed> $attributes Additional attributes
=======
     * @param string|Expr $name       Name
     * @param array       $attributes Additional attributes
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct($name, array $attributes = []) {
        $this->attributes = $attributes;
        $this->name = $name;
    }

<<<<<<< HEAD
    public function getSubNodeNames(): array {
        return ['name'];
    }

    public function getType(): string {
=======
    public function getSubNodeNames() : array {
        return ['name'];
    }
    
    public function getType() : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return 'Expr_Variable';
    }
}
