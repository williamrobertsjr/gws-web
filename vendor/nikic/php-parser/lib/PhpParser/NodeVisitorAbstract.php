<?php declare(strict_types=1);

namespace PhpParser;

/**
 * @codeCoverageIgnore
 */
<<<<<<< HEAD
abstract class NodeVisitorAbstract implements NodeVisitor {
=======
class NodeVisitorAbstract implements NodeVisitor
{
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function beforeTraverse(array $nodes) {
        return null;
    }

    public function enterNode(Node $node) {
        return null;
    }

    public function leaveNode(Node $node) {
        return null;
    }

    public function afterTraverse(array $nodes) {
        return null;
    }
}
