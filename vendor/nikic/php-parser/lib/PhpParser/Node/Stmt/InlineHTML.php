<?php declare(strict_types=1);

namespace PhpParser\Node\Stmt;

use PhpParser\Node\Stmt;

<<<<<<< HEAD
class InlineHTML extends Stmt {
    /** @var string String */
    public string $value;
=======
class InlineHTML extends Stmt
{
    /** @var string String */
    public $value;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Constructs an inline HTML node.
     *
<<<<<<< HEAD
     * @param string $value String
     * @param array<string, mixed> $attributes Additional attributes
=======
     * @param string $value      String
     * @param array  $attributes Additional attributes
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct(string $value, array $attributes = []) {
        $this->attributes = $attributes;
        $this->value = $value;
    }

<<<<<<< HEAD
    public function getSubNodeNames(): array {
        return ['value'];
    }

    public function getType(): string {
=======
    public function getSubNodeNames() : array {
        return ['value'];
    }
    
    public function getType() : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return 'Stmt_InlineHTML';
    }
}
