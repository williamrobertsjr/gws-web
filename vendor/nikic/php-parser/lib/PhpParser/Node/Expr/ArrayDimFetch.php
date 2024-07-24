<?php declare(strict_types=1);

namespace PhpParser\Node\Expr;

use PhpParser\Node\Expr;

<<<<<<< HEAD
class ArrayDimFetch extends Expr {
    /** @var Expr Variable */
    public Expr $var;
    /** @var null|Expr Array index / dim */
    public ?Expr $dim;
=======
class ArrayDimFetch extends Expr
{
    /** @var Expr Variable */
    public $var;
    /** @var null|Expr Array index / dim */
    public $dim;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Constructs an array index fetch node.
     *
<<<<<<< HEAD
     * @param Expr $var Variable
     * @param null|Expr $dim Array index / dim
     * @param array<string, mixed> $attributes Additional attributes
     */
    public function __construct(Expr $var, ?Expr $dim = null, array $attributes = []) {
=======
     * @param Expr      $var        Variable
     * @param null|Expr $dim        Array index / dim
     * @param array     $attributes Additional attributes
     */
    public function __construct(Expr $var, Expr $dim = null, array $attributes = []) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $this->attributes = $attributes;
        $this->var = $var;
        $this->dim = $dim;
    }

<<<<<<< HEAD
    public function getSubNodeNames(): array {
        return ['var', 'dim'];
    }

    public function getType(): string {
=======
    public function getSubNodeNames() : array {
        return ['var', 'dim'];
    }
    
    public function getType() : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return 'Expr_ArrayDimFetch';
    }
}
