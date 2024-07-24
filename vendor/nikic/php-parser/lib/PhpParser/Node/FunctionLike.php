<?php declare(strict_types=1);

namespace PhpParser\Node;

use PhpParser\Node;

<<<<<<< HEAD
interface FunctionLike extends Node {
    /**
     * Whether to return by reference
     */
    public function returnsByRef(): bool;
=======
interface FunctionLike extends Node
{
    /**
     * Whether to return by reference
     *
     * @return bool
     */
    public function returnsByRef() : bool;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * List of parameters
     *
     * @return Param[]
     */
<<<<<<< HEAD
    public function getParams(): array;
=======
    public function getParams() : array;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Get the declared return type or null
     *
     * @return null|Identifier|Name|ComplexType
     */
    public function getReturnType();

    /**
     * The function body
     *
     * @return Stmt[]|null
     */
<<<<<<< HEAD
    public function getStmts(): ?array;
=======
    public function getStmts();
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Get PHP attribute groups.
     *
     * @return AttributeGroup[]
     */
<<<<<<< HEAD
    public function getAttrGroups(): array;
=======
    public function getAttrGroups() : array;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
}
