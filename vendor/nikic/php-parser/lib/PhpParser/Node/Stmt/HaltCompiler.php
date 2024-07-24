<?php declare(strict_types=1);

namespace PhpParser\Node\Stmt;

use PhpParser\Node\Stmt;

<<<<<<< HEAD
class HaltCompiler extends Stmt {
    /** @var string Remaining text after halt compiler statement. */
    public string $remaining;
=======
class HaltCompiler extends Stmt
{
    /** @var string Remaining text after halt compiler statement. */
    public $remaining;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Constructs a __halt_compiler node.
     *
<<<<<<< HEAD
     * @param string $remaining Remaining text after halt compiler statement.
     * @param array<string, mixed> $attributes Additional attributes
=======
     * @param string $remaining  Remaining text after halt compiler statement.
     * @param array  $attributes Additional attributes
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct(string $remaining, array $attributes = []) {
        $this->attributes = $attributes;
        $this->remaining = $remaining;
    }

<<<<<<< HEAD
    public function getSubNodeNames(): array {
        return ['remaining'];
    }

    public function getType(): string {
=======
    public function getSubNodeNames() : array {
        return ['remaining'];
    }
    
    public function getType() : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return 'Stmt_HaltCompiler';
    }
}
