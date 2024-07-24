<?php declare(strict_types=1);

namespace PhpParser\Node\Expr;

use PhpParser\Node\Expr;
<<<<<<< HEAD
use PhpParser\Node\InterpolatedStringPart;

class ShellExec extends Expr {
    /** @var (Expr|InterpolatedStringPart)[] Interpolated string array */
    public array $parts;
=======

class ShellExec extends Expr
{
    /** @var array Encapsed string array */
    public $parts;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Constructs a shell exec (backtick) node.
     *
<<<<<<< HEAD
     * @param (Expr|InterpolatedStringPart)[] $parts Interpolated string array
     * @param array<string, mixed> $attributes Additional attributes
=======
     * @param array $parts      Encapsed string array
     * @param array $attributes Additional attributes
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct(array $parts, array $attributes = []) {
        $this->attributes = $attributes;
        $this->parts = $parts;
    }

<<<<<<< HEAD
    public function getSubNodeNames(): array {
        return ['parts'];
    }

    public function getType(): string {
=======
    public function getSubNodeNames() : array {
        return ['parts'];
    }
    
    public function getType() : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return 'Expr_ShellExec';
    }
}
