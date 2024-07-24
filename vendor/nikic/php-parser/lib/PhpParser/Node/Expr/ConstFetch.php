<?php declare(strict_types=1);

namespace PhpParser\Node\Expr;

use PhpParser\Node\Expr;
use PhpParser\Node\Name;

<<<<<<< HEAD
class ConstFetch extends Expr {
    /** @var Name Constant name */
    public Name $name;
=======
class ConstFetch extends Expr
{
    /** @var Name Constant name */
    public $name;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Constructs a const fetch node.
     *
<<<<<<< HEAD
     * @param Name $name Constant name
     * @param array<string, mixed> $attributes Additional attributes
=======
     * @param Name  $name       Constant name
     * @param array $attributes Additional attributes
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct(Name $name, array $attributes = []) {
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
        return 'Expr_ConstFetch';
    }
}
