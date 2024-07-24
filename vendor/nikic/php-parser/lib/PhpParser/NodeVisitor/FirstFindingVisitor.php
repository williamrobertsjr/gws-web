<?php declare(strict_types=1);

namespace PhpParser\NodeVisitor;

use PhpParser\Node;
<<<<<<< HEAD
use PhpParser\NodeVisitor;
=======
use PhpParser\NodeTraverser;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
use PhpParser\NodeVisitorAbstract;

/**
 * This visitor can be used to find the first node satisfying some criterion determined by
 * a filter callback.
 */
<<<<<<< HEAD
class FirstFindingVisitor extends NodeVisitorAbstract {
    /** @var callable Filter callback */
    protected $filterCallback;
    /** @var null|Node Found node */
    protected ?Node $foundNode;
=======
class FirstFindingVisitor extends NodeVisitorAbstract
{
    /** @var callable Filter callback */
    protected $filterCallback;
    /** @var null|Node Found node */
    protected $foundNode;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    public function __construct(callable $filterCallback) {
        $this->filterCallback = $filterCallback;
    }

    /**
     * Get found node satisfying the filter callback.
     *
     * Returns null if no node satisfies the filter callback.
     *
     * @return null|Node Found node (or null if not found)
     */
<<<<<<< HEAD
    public function getFoundNode(): ?Node {
        return $this->foundNode;
    }

    public function beforeTraverse(array $nodes): ?array {
=======
    public function getFoundNode() {
        return $this->foundNode;
    }

    public function beforeTraverse(array $nodes) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $this->foundNode = null;

        return null;
    }

    public function enterNode(Node $node) {
        $filterCallback = $this->filterCallback;
        if ($filterCallback($node)) {
            $this->foundNode = $node;
<<<<<<< HEAD
            return NodeVisitor::STOP_TRAVERSAL;
=======
            return NodeTraverser::STOP_TRAVERSAL;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        }

        return null;
    }
}
