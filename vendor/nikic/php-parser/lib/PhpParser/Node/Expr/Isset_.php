<?php declare(strict_types=1);

namespace PhpParser\Node\Expr;

use PhpParser\Node\Expr;

<<<<<<< HEAD
class Isset_ extends Expr {
    /** @var Expr[] Variables */
    public array $vars;
=======
class Isset_ extends Expr
{
    /** @var Expr[] Variables */
    public $vars;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Constructs an array node.
     *
<<<<<<< HEAD
     * @param Expr[] $vars Variables
     * @param array<string, mixed> $attributes Additional attributes
=======
     * @param Expr[] $vars       Variables
     * @param array  $attributes Additional attributes
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct(array $vars, array $attributes = []) {
        $this->attributes = $attributes;
        $this->vars = $vars;
    }

<<<<<<< HEAD
    public function getSubNodeNames(): array {
        return ['vars'];
    }

    public function getType(): string {
=======
    public function getSubNodeNames() : array {
        return ['vars'];
    }
    
    public function getType() : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return 'Expr_Isset';
    }
}
