<?php declare(strict_types=1);

namespace PhpParser\Node\Stmt;

use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt;

<<<<<<< HEAD
class Goto_ extends Stmt {
    /** @var Identifier Name of label to jump to */
    public Identifier $name;
=======
class Goto_ extends Stmt
{
    /** @var Identifier Name of label to jump to */
    public $name;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Constructs a goto node.
     *
<<<<<<< HEAD
     * @param string|Identifier $name Name of label to jump to
     * @param array<string, mixed> $attributes Additional attributes
=======
     * @param string|Identifier $name       Name of label to jump to
     * @param array             $attributes Additional attributes
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct($name, array $attributes = []) {
        $this->attributes = $attributes;
        $this->name = \is_string($name) ? new Identifier($name) : $name;
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
        return 'Stmt_Goto';
    }
}
