<?php declare(strict_types=1);

namespace PhpParser\Node\Stmt;

use PhpParser\Node;

<<<<<<< HEAD
class Echo_ extends Node\Stmt {
    /** @var Node\Expr[] Expressions */
    public array $exprs;
=======
class Echo_ extends Node\Stmt
{
    /** @var Node\Expr[] Expressions */
    public $exprs;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Constructs an echo node.
     *
<<<<<<< HEAD
     * @param Node\Expr[] $exprs Expressions
     * @param array<string, mixed> $attributes Additional attributes
=======
     * @param Node\Expr[] $exprs      Expressions
     * @param array       $attributes Additional attributes
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct(array $exprs, array $attributes = []) {
        $this->attributes = $attributes;
        $this->exprs = $exprs;
    }

<<<<<<< HEAD
    public function getSubNodeNames(): array {
        return ['exprs'];
    }

    public function getType(): string {
=======
    public function getSubNodeNames() : array {
        return ['exprs'];
    }
    
    public function getType() : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return 'Stmt_Echo';
    }
}
