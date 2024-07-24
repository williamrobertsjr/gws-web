<?php declare(strict_types=1);

namespace PhpParser\Node\Expr;

use PhpParser\Node\Expr;

<<<<<<< HEAD
class PreInc extends Expr {
    /** @var Expr Variable */
    public Expr $var;
=======
class PreInc extends Expr
{
    /** @var Expr Variable */
    public $var;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Constructs a pre increment node.
     *
<<<<<<< HEAD
     * @param Expr $var Variable
     * @param array<string, mixed> $attributes Additional attributes
=======
     * @param Expr  $var        Variable
     * @param array $attributes Additional attributes
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct(Expr $var, array $attributes = []) {
        $this->attributes = $attributes;
        $this->var = $var;
    }

<<<<<<< HEAD
    public function getSubNodeNames(): array {
        return ['var'];
    }

    public function getType(): string {
=======
    public function getSubNodeNames() : array {
        return ['var'];
    }
    
    public function getType() : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return 'Expr_PreInc';
    }
}
