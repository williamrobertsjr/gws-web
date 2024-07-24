<?php declare(strict_types=1);

namespace PhpParser;

<<<<<<< HEAD
abstract class NodeAbstract implements Node, \JsonSerializable {
    /** @var array<string, mixed> Attributes */
    protected array $attributes;
=======
abstract class NodeAbstract implements Node, \JsonSerializable
{
    protected $attributes;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Creates a Node.
     *
<<<<<<< HEAD
     * @param array<string, mixed> $attributes Array of attributes
=======
     * @param array $attributes Array of attributes
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct(array $attributes = []) {
        $this->attributes = $attributes;
    }

    /**
     * Gets line the node started in (alias of getStartLine).
     *
     * @return int Start line (or -1 if not available)
<<<<<<< HEAD
     * @phpstan-return -1|positive-int
     */
    public function getLine(): int {
=======
     */
    public function getLine() : int {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this->attributes['startLine'] ?? -1;
    }

    /**
     * Gets line the node started in.
     *
     * Requires the 'startLine' attribute to be enabled in the lexer (enabled by default).
     *
     * @return int Start line (or -1 if not available)
<<<<<<< HEAD
     * @phpstan-return -1|positive-int
     */
    public function getStartLine(): int {
=======
     */
    public function getStartLine() : int {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this->attributes['startLine'] ?? -1;
    }

    /**
     * Gets the line the node ended in.
     *
     * Requires the 'endLine' attribute to be enabled in the lexer (enabled by default).
     *
     * @return int End line (or -1 if not available)
<<<<<<< HEAD
     * @phpstan-return -1|positive-int
     */
    public function getEndLine(): int {
=======
     */
    public function getEndLine() : int {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this->attributes['endLine'] ?? -1;
    }

    /**
     * Gets the token offset of the first token that is part of this node.
     *
     * The offset is an index into the array returned by Lexer::getTokens().
     *
     * Requires the 'startTokenPos' attribute to be enabled in the lexer (DISABLED by default).
     *
     * @return int Token start position (or -1 if not available)
     */
<<<<<<< HEAD
    public function getStartTokenPos(): int {
=======
    public function getStartTokenPos() : int {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this->attributes['startTokenPos'] ?? -1;
    }

    /**
     * Gets the token offset of the last token that is part of this node.
     *
     * The offset is an index into the array returned by Lexer::getTokens().
     *
     * Requires the 'endTokenPos' attribute to be enabled in the lexer (DISABLED by default).
     *
     * @return int Token end position (or -1 if not available)
     */
<<<<<<< HEAD
    public function getEndTokenPos(): int {
=======
    public function getEndTokenPos() : int {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this->attributes['endTokenPos'] ?? -1;
    }

    /**
     * Gets the file offset of the first character that is part of this node.
     *
     * Requires the 'startFilePos' attribute to be enabled in the lexer (DISABLED by default).
     *
     * @return int File start position (or -1 if not available)
     */
<<<<<<< HEAD
    public function getStartFilePos(): int {
=======
    public function getStartFilePos() : int {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this->attributes['startFilePos'] ?? -1;
    }

    /**
     * Gets the file offset of the last character that is part of this node.
     *
     * Requires the 'endFilePos' attribute to be enabled in the lexer (DISABLED by default).
     *
     * @return int File end position (or -1 if not available)
     */
<<<<<<< HEAD
    public function getEndFilePos(): int {
=======
    public function getEndFilePos() : int {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this->attributes['endFilePos'] ?? -1;
    }

    /**
     * Gets all comments directly preceding this node.
     *
     * The comments are also available through the "comments" attribute.
     *
     * @return Comment[]
     */
<<<<<<< HEAD
    public function getComments(): array {
=======
    public function getComments() : array {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this->attributes['comments'] ?? [];
    }

    /**
     * Gets the doc comment of the node.
     *
     * @return null|Comment\Doc Doc comment object or null
     */
<<<<<<< HEAD
    public function getDocComment(): ?Comment\Doc {
=======
    public function getDocComment() {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $comments = $this->getComments();
        for ($i = count($comments) - 1; $i >= 0; $i--) {
            $comment = $comments[$i];
            if ($comment instanceof Comment\Doc) {
                return $comment;
            }
        }

        return null;
    }

    /**
     * Sets the doc comment of the node.
     *
     * This will either replace an existing doc comment or add it to the comments array.
     *
     * @param Comment\Doc $docComment Doc comment to set
     */
<<<<<<< HEAD
    public function setDocComment(Comment\Doc $docComment): void {
=======
    public function setDocComment(Comment\Doc $docComment) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $comments = $this->getComments();
        for ($i = count($comments) - 1; $i >= 0; $i--) {
            if ($comments[$i] instanceof Comment\Doc) {
                // Replace existing doc comment.
                $comments[$i] = $docComment;
                $this->setAttribute('comments', $comments);
                return;
            }
        }

        // Append new doc comment.
        $comments[] = $docComment;
        $this->setAttribute('comments', $comments);
    }

<<<<<<< HEAD
    public function setAttribute(string $key, $value): void {
        $this->attributes[$key] = $value;
    }

    public function hasAttribute(string $key): bool {
=======
    public function setAttribute(string $key, $value) {
        $this->attributes[$key] = $value;
    }

    public function hasAttribute(string $key) : bool {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return array_key_exists($key, $this->attributes);
    }

    public function getAttribute(string $key, $default = null) {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        return $default;
    }

<<<<<<< HEAD
    public function getAttributes(): array {
        return $this->attributes;
    }

    public function setAttributes(array $attributes): void {
=======
    public function getAttributes() : array {
        return $this->attributes;
    }

    public function setAttributes(array $attributes) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $this->attributes = $attributes;
    }

    /**
<<<<<<< HEAD
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array {
=======
     * @return array
     */
    public function jsonSerialize() : array {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return ['nodeType' => $this->getType()] + get_object_vars($this);
    }
}
