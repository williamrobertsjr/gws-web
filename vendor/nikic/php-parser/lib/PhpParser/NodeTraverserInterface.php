<?php declare(strict_types=1);

namespace PhpParser;

<<<<<<< HEAD
interface NodeTraverserInterface {
=======
interface NodeTraverserInterface
{
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    /**
     * Adds a visitor.
     *
     * @param NodeVisitor $visitor Visitor to add
     */
<<<<<<< HEAD
    public function addVisitor(NodeVisitor $visitor): void;

    /**
     * Removes an added visitor.
     */
    public function removeVisitor(NodeVisitor $visitor): void;
=======
    public function addVisitor(NodeVisitor $visitor);

    /**
     * Removes an added visitor.
     *
     * @param NodeVisitor $visitor
     */
    public function removeVisitor(NodeVisitor $visitor);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Traverses an array of nodes using the registered visitors.
     *
     * @param Node[] $nodes Array of nodes
     *
     * @return Node[] Traversed array of nodes
     */
<<<<<<< HEAD
    public function traverse(array $nodes): array;
=======
    public function traverse(array $nodes) : array;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
}
