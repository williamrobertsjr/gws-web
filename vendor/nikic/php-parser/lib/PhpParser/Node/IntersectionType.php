<?php declare(strict_types=1);

namespace PhpParser\Node;

<<<<<<< HEAD
class IntersectionType extends ComplexType {
    /** @var (Identifier|Name)[] Types */
    public array $types;
=======
use PhpParser\NodeAbstract;

class IntersectionType extends ComplexType
{
    /** @var (Identifier|Name)[] Types */
    public $types;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Constructs an intersection type.
     *
<<<<<<< HEAD
     * @param (Identifier|Name)[] $types Types
     * @param array<string, mixed> $attributes Additional attributes
=======
     * @param (Identifier|Name)[] $types      Types
     * @param array               $attributes Additional attributes
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct(array $types, array $attributes = []) {
        $this->attributes = $attributes;
        $this->types = $types;
    }

<<<<<<< HEAD
    public function getSubNodeNames(): array {
        return ['types'];
    }

    public function getType(): string {
=======
    public function getSubNodeNames() : array {
        return ['types'];
    }

    public function getType() : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return 'IntersectionType';
    }
}
