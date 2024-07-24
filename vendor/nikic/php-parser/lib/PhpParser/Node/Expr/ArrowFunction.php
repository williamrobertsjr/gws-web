<?php declare(strict_types=1);

namespace PhpParser\Node\Expr;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\FunctionLike;

<<<<<<< HEAD
class ArrowFunction extends Expr implements FunctionLike {
    /** @var bool Whether the closure is static */
    public bool $static;

    /** @var bool Whether to return by reference */
    public bool $byRef;

    /** @var Node\Param[] */
    public array $params = [];

    /** @var null|Node\Identifier|Node\Name|Node\ComplexType */
    public ?Node $returnType;

    /** @var Expr Expression body */
    public Expr $expr;
    /** @var Node\AttributeGroup[] */
    public array $attrGroups;

    /**
     * @param array{
     *     expr: Expr,
     *     static?: bool,
     *     byRef?: bool,
     *     params?: Node\Param[],
     *     returnType?: null|Node\Identifier|Node\Name|Node\ComplexType,
     *     attrGroups?: Node\AttributeGroup[]
     * } $subNodes Array of the following subnodes:
     *             'expr'                  : Expression body
     *             'static'     => false   : Whether the closure is static
     *             'byRef'      => false   : Whether to return by reference
     *             'params'     => array() : Parameters
     *             'returnType' => null    : Return type
     *             'attrGroups' => array() : PHP attribute groups
     * @param array<string, mixed> $attributes Additional attributes
     */
    public function __construct(array $subNodes, array $attributes = []) {
=======
class ArrowFunction extends Expr implements FunctionLike
{
    /** @var bool */
    public $static;

    /** @var bool */
    public $byRef;

    /** @var Node\Param[] */
    public $params = [];

    /** @var null|Node\Identifier|Node\Name|Node\ComplexType */
    public $returnType;

    /** @var Expr */
    public $expr;
    /** @var Node\AttributeGroup[] */
    public $attrGroups;

    /**
     * @param array $subNodes   Array of the following optional subnodes:
     *                          'static'     => false   : Whether the closure is static
     *                          'byRef'      => false   : Whether to return by reference
     *                          'params'     => array() : Parameters
     *                          'returnType' => null    : Return type
     *                          'expr'       => Expr    : Expression body
     *                          'attrGroups' => array() : PHP attribute groups
     * @param array $attributes Additional attributes
     */
    public function __construct(array $subNodes = [], array $attributes = []) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $this->attributes = $attributes;
        $this->static = $subNodes['static'] ?? false;
        $this->byRef = $subNodes['byRef'] ?? false;
        $this->params = $subNodes['params'] ?? [];
<<<<<<< HEAD
        $this->returnType = $subNodes['returnType'] ?? null;
=======
        $returnType = $subNodes['returnType'] ?? null;
        $this->returnType = \is_string($returnType) ? new Node\Identifier($returnType) : $returnType;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $this->expr = $subNodes['expr'];
        $this->attrGroups = $subNodes['attrGroups'] ?? [];
    }

<<<<<<< HEAD
    public function getSubNodeNames(): array {
        return ['attrGroups', 'static', 'byRef', 'params', 'returnType', 'expr'];
    }

    public function returnsByRef(): bool {
        return $this->byRef;
    }

    public function getParams(): array {
=======
    public function getSubNodeNames() : array {
        return ['attrGroups', 'static', 'byRef', 'params', 'returnType', 'expr'];
    }

    public function returnsByRef() : bool {
        return $this->byRef;
    }

    public function getParams() : array {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this->params;
    }

    public function getReturnType() {
        return $this->returnType;
    }

<<<<<<< HEAD
    public function getAttrGroups(): array {
=======
    public function getAttrGroups() : array {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this->attrGroups;
    }

    /**
     * @return Node\Stmt\Return_[]
     */
<<<<<<< HEAD
    public function getStmts(): array {
        return [new Node\Stmt\Return_($this->expr)];
    }

    public function getType(): string {
=======
    public function getStmts() : array {
        return [new Node\Stmt\Return_($this->expr)];
    }

    public function getType() : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return 'Expr_ArrowFunction';
    }
}
