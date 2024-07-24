<?php declare(strict_types=1);
/*
 * This file is part of sebastian/complexity.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\Complexity;

use PhpParser\Error;
<<<<<<< HEAD
=======
use PhpParser\Lexer;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\NodeVisitor\ParentConnectingVisitor;
<<<<<<< HEAD
=======
use PhpParser\Parser;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
use PhpParser\ParserFactory;

final class Calculator
{
    /**
     * @throws RuntimeException
     */
    public function calculateForSourceFile(string $sourceFile): ComplexityCollection
    {
        return $this->calculateForSourceString(file_get_contents($sourceFile));
    }

    /**
     * @throws RuntimeException
     */
    public function calculateForSourceString(string $source): ComplexityCollection
    {
        try {
<<<<<<< HEAD
            $nodes = (new ParserFactory)->createForHostVersion()->parse($source);
=======
            $nodes = $this->parser()->parse($source);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

            assert($nodes !== null);

            return $this->calculateForAbstractSyntaxTree($nodes);

            // @codeCoverageIgnoreStart
        } catch (Error $error) {
            throw new RuntimeException(
                $error->getMessage(),
                (int) $error->getCode(),
                $error
            );
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param Node[] $nodes
     *
     * @throws RuntimeException
     */
    public function calculateForAbstractSyntaxTree(array $nodes): ComplexityCollection
    {
        $traverser                    = new NodeTraverser;
        $complexityCalculatingVisitor = new ComplexityCalculatingVisitor(true);

        $traverser->addVisitor(new NameResolver);
        $traverser->addVisitor(new ParentConnectingVisitor);
        $traverser->addVisitor($complexityCalculatingVisitor);

        try {
            /* @noinspection UnusedFunctionResultInspection */
            $traverser->traverse($nodes);
            // @codeCoverageIgnoreStart
        } catch (Error $error) {
            throw new RuntimeException(
                $error->getMessage(),
                (int) $error->getCode(),
                $error
            );
        }
        // @codeCoverageIgnoreEnd

        return $complexityCalculatingVisitor->result();
    }
<<<<<<< HEAD
=======

    private function parser(): Parser
    {
        return (new ParserFactory)->create(ParserFactory::PREFER_PHP7, new Lexer);
    }
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
}
