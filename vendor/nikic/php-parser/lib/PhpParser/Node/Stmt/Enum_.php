<?php declare(strict_types=1);

namespace PhpParser\Node\Stmt;

use PhpParser\Node;

<<<<<<< HEAD
class Enum_ extends ClassLike {
    /** @var null|Node\Identifier Scalar Type */
    public ?Node $scalarType;
    /** @var Node\Name[] Names of implemented interfaces */
    public array $implements;

    /**
     * @param string|Node\Identifier|null $name Name
     * @param array{
     *     scalarType?: Node\Identifier|null,
     *     implements?: Node\Name[],
     *     stmts?: Node\Stmt[],
     *     attrGroups?: Node\AttributeGroup[],
     * } $subNodes Array of the following optional subnodes:
     *             'scalarType'  => null    : Scalar type
     *             'implements'  => array() : Names of implemented interfaces
     *             'stmts'       => array() : Statements
     *             'attrGroups'  => array() : PHP attribute groups
     * @param array<string, mixed> $attributes Additional attributes
=======
class Enum_ extends ClassLike
{
    /** @var null|Node\Identifier Scalar Type */
    public $scalarType;
    /** @var Node\Name[] Names of implemented interfaces */
    public $implements;

    /**
     * @param string|Node\Identifier|null $name       Name
     * @param array                       $subNodes   Array of the following optional subnodes:
     *                                                'scalarType'  => null    : Scalar type
     *                                                'implements'  => array() : Names of implemented interfaces
     *                                                'stmts'       => array() : Statements
     *                                                'attrGroups'  => array() : PHP attribute groups
     * @param array                       $attributes Additional attributes
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct($name, array $subNodes = [], array $attributes = []) {
        $this->name = \is_string($name) ? new Node\Identifier($name) : $name;
        $this->scalarType = $subNodes['scalarType'] ?? null;
        $this->implements = $subNodes['implements'] ?? [];
        $this->stmts = $subNodes['stmts'] ?? [];
        $this->attrGroups = $subNodes['attrGroups'] ?? [];

        parent::__construct($attributes);
    }

<<<<<<< HEAD
    public function getSubNodeNames(): array {
        return ['attrGroups', 'name', 'scalarType', 'implements', 'stmts'];
    }

    public function getType(): string {
=======
    public function getSubNodeNames() : array {
        return ['attrGroups', 'name', 'scalarType', 'implements', 'stmts'];
    }

    public function getType() : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return 'Stmt_Enum';
    }
}
