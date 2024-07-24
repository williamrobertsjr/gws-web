<?php declare(strict_types=1);

namespace PhpParser\Node\Stmt;

use PhpParser\Node;

<<<<<<< HEAD
class Else_ extends Node\Stmt {
    /** @var Node\Stmt[] Statements */
    public array $stmts;
=======
class Else_ extends Node\Stmt
{
    /** @var Node\Stmt[] Statements */
    public $stmts;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Constructs an else node.
     *
<<<<<<< HEAD
     * @param Node\Stmt[] $stmts Statements
     * @param array<string, mixed> $attributes Additional attributes
=======
     * @param Node\Stmt[] $stmts      Statements
     * @param array       $attributes Additional attributes
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct(array $stmts = [], array $attributes = []) {
        $this->attributes = $attributes;
        $this->stmts = $stmts;
    }

<<<<<<< HEAD
    public function getSubNodeNames(): array {
        return ['stmts'];
    }

    public function getType(): string {
=======
    public function getSubNodeNames() : array {
        return ['stmts'];
    }
    
    public function getType() : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return 'Stmt_Else';
    }
}
