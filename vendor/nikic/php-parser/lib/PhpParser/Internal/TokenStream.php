<?php declare(strict_types=1);

namespace PhpParser\Internal;

<<<<<<< HEAD
use PhpParser\Token;

=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
/**
 * Provides operations on token streams, for use by pretty printer.
 *
 * @internal
 */
<<<<<<< HEAD
class TokenStream {
    /** @var Token[] Tokens (in PhpToken::tokenize() format) */
    private array $tokens;
    /** @var int[] Map from position to indentation */
    private array $indentMap;
=======
class TokenStream
{
    /** @var array Tokens (in token_get_all format) */
    private $tokens;
    /** @var int[] Map from position to indentation */
    private $indentMap;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Create token stream instance.
     *
<<<<<<< HEAD
     * @param Token[] $tokens Tokens in PhpToken::tokenize() format
=======
     * @param array $tokens Tokens in token_get_all() format
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct(array $tokens) {
        $this->tokens = $tokens;
        $this->indentMap = $this->calcIndentMap();
    }

    /**
     * Whether the given position is immediately surrounded by parenthesis.
     *
     * @param int $startPos Start position
<<<<<<< HEAD
     * @param int $endPos End position
     */
    public function haveParens(int $startPos, int $endPos): bool {
=======
     * @param int $endPos   End position
     *
     * @return bool
     */
    public function haveParens(int $startPos, int $endPos) : bool {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this->haveTokenImmediatelyBefore($startPos, '(')
            && $this->haveTokenImmediatelyAfter($endPos, ')');
    }

    /**
     * Whether the given position is immediately surrounded by braces.
     *
     * @param int $startPos Start position
<<<<<<< HEAD
     * @param int $endPos End position
     */
    public function haveBraces(int $startPos, int $endPos): bool {
=======
     * @param int $endPos   End position
     *
     * @return bool
     */
    public function haveBraces(int $startPos, int $endPos) : bool {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return ($this->haveTokenImmediatelyBefore($startPos, '{')
                || $this->haveTokenImmediatelyBefore($startPos, T_CURLY_OPEN))
            && $this->haveTokenImmediatelyAfter($endPos, '}');
    }

    /**
     * Check whether the position is directly preceded by a certain token type.
     *
     * During this check whitespace and comments are skipped.
     *
<<<<<<< HEAD
     * @param int $pos Position before which the token should occur
=======
     * @param int        $pos               Position before which the token should occur
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     * @param int|string $expectedTokenType Token to check for
     *
     * @return bool Whether the expected token was found
     */
<<<<<<< HEAD
    public function haveTokenImmediatelyBefore(int $pos, $expectedTokenType): bool {
        $tokens = $this->tokens;
        $pos--;
        for (; $pos >= 0; $pos--) {
            $token = $tokens[$pos];
            if ($token->is($expectedTokenType)) {
                return true;
            }
            if (!$token->isIgnorable()) {
=======
    public function haveTokenImmediatelyBefore(int $pos, $expectedTokenType) : bool {
        $tokens = $this->tokens;
        $pos--;
        for (; $pos >= 0; $pos--) {
            $tokenType = $tokens[$pos][0];
            if ($tokenType === $expectedTokenType) {
                return true;
            }
            if ($tokenType !== \T_WHITESPACE
                && $tokenType !== \T_COMMENT && $tokenType !== \T_DOC_COMMENT) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                break;
            }
        }
        return false;
    }

    /**
     * Check whether the position is directly followed by a certain token type.
     *
     * During this check whitespace and comments are skipped.
     *
<<<<<<< HEAD
     * @param int $pos Position after which the token should occur
=======
     * @param int        $pos               Position after which the token should occur
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     * @param int|string $expectedTokenType Token to check for
     *
     * @return bool Whether the expected token was found
     */
<<<<<<< HEAD
    public function haveTokenImmediatelyAfter(int $pos, $expectedTokenType): bool {
        $tokens = $this->tokens;
        $pos++;
        for ($c = \count($tokens); $pos < $c; $pos++) {
            $token = $tokens[$pos];
            if ($token->is($expectedTokenType)) {
                return true;
            }
            if (!$token->isIgnorable()) {
=======
    public function haveTokenImmediatelyAfter(int $pos, $expectedTokenType) : bool {
        $tokens = $this->tokens;
        $pos++;
        for (; $pos < \count($tokens); $pos++) {
            $tokenType = $tokens[$pos][0];
            if ($tokenType === $expectedTokenType) {
                return true;
            }
            if ($tokenType !== \T_WHITESPACE
                && $tokenType !== \T_COMMENT && $tokenType !== \T_DOC_COMMENT) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                break;
            }
        }
        return false;
    }

<<<<<<< HEAD
    /** @param int|string|(int|string)[] $skipTokenType */
    public function skipLeft(int $pos, $skipTokenType): int {
=======
    public function skipLeft(int $pos, $skipTokenType) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $tokens = $this->tokens;

        $pos = $this->skipLeftWhitespace($pos);
        if ($skipTokenType === \T_WHITESPACE) {
            return $pos;
        }

<<<<<<< HEAD
        if (!$tokens[$pos]->is($skipTokenType)) {
=======
        if ($tokens[$pos][0] !== $skipTokenType) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            // Shouldn't happen. The skip token MUST be there
            throw new \Exception('Encountered unexpected token');
        }
        $pos--;

        return $this->skipLeftWhitespace($pos);
    }

<<<<<<< HEAD
    /** @param int|string|(int|string)[] $skipTokenType */
    public function skipRight(int $pos, $skipTokenType): int {
=======
    public function skipRight(int $pos, $skipTokenType) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $tokens = $this->tokens;

        $pos = $this->skipRightWhitespace($pos);
        if ($skipTokenType === \T_WHITESPACE) {
            return $pos;
        }

<<<<<<< HEAD
        if (!$tokens[$pos]->is($skipTokenType)) {
=======
        if ($tokens[$pos][0] !== $skipTokenType) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            // Shouldn't happen. The skip token MUST be there
            throw new \Exception('Encountered unexpected token');
        }
        $pos++;

        return $this->skipRightWhitespace($pos);
    }

    /**
     * Return first non-whitespace token position smaller or equal to passed position.
     *
     * @param int $pos Token position
     * @return int Non-whitespace token position
     */
<<<<<<< HEAD
    public function skipLeftWhitespace(int $pos): int {
        $tokens = $this->tokens;
        for (; $pos >= 0; $pos--) {
            if (!$tokens[$pos]->isIgnorable()) {
=======
    public function skipLeftWhitespace(int $pos) {
        $tokens = $this->tokens;
        for (; $pos >= 0; $pos--) {
            $type = $tokens[$pos][0];
            if ($type !== \T_WHITESPACE && $type !== \T_COMMENT && $type !== \T_DOC_COMMENT) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                break;
            }
        }
        return $pos;
    }

    /**
     * Return first non-whitespace position greater or equal to passed position.
     *
     * @param int $pos Token position
     * @return int Non-whitespace token position
     */
<<<<<<< HEAD
    public function skipRightWhitespace(int $pos): int {
        $tokens = $this->tokens;
        for ($count = \count($tokens); $pos < $count; $pos++) {
            if (!$tokens[$pos]->isIgnorable()) {
=======
    public function skipRightWhitespace(int $pos) {
        $tokens = $this->tokens;
        for ($count = \count($tokens); $pos < $count; $pos++) {
            $type = $tokens[$pos][0];
            if ($type !== \T_WHITESPACE && $type !== \T_COMMENT && $type !== \T_DOC_COMMENT) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                break;
            }
        }
        return $pos;
    }

<<<<<<< HEAD
    /** @param int|string|(int|string)[] $findTokenType */
    public function findRight(int $pos, $findTokenType): int {
        $tokens = $this->tokens;
        for ($count = \count($tokens); $pos < $count; $pos++) {
            if ($tokens[$pos]->is($findTokenType)) {
=======
    public function findRight(int $pos, $findTokenType) {
        $tokens = $this->tokens;
        for ($count = \count($tokens); $pos < $count; $pos++) {
            $type = $tokens[$pos][0];
            if ($type === $findTokenType) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                return $pos;
            }
        }
        return -1;
    }

    /**
     * Whether the given position range contains a certain token type.
     *
     * @param int $startPos Starting position (inclusive)
     * @param int $endPos Ending position (exclusive)
     * @param int|string $tokenType Token type to look for
     * @return bool Whether the token occurs in the given range
     */
<<<<<<< HEAD
    public function haveTokenInRange(int $startPos, int $endPos, $tokenType): bool {
        $tokens = $this->tokens;
        for ($pos = $startPos; $pos < $endPos; $pos++) {
            if ($tokens[$pos]->is($tokenType)) {
=======
    public function haveTokenInRange(int $startPos, int $endPos, $tokenType) {
        $tokens = $this->tokens;
        for ($pos = $startPos; $pos < $endPos; $pos++) {
            if ($tokens[$pos][0] === $tokenType) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                return true;
            }
        }
        return false;
    }

<<<<<<< HEAD
=======
    public function haveBracesInRange(int $startPos, int $endPos) {
        return $this->haveTokenInRange($startPos, $endPos, '{')
            || $this->haveTokenInRange($startPos, $endPos, T_CURLY_OPEN)
            || $this->haveTokenInRange($startPos, $endPos, '}');
    }

>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function haveTagInRange(int $startPos, int $endPos): bool {
        return $this->haveTokenInRange($startPos, $endPos, \T_OPEN_TAG)
            || $this->haveTokenInRange($startPos, $endPos, \T_CLOSE_TAG);
    }

    /**
     * Get indentation before token position.
     *
     * @param int $pos Token position
     *
     * @return int Indentation depth (in spaces)
     */
<<<<<<< HEAD
    public function getIndentationBefore(int $pos): int {
=======
    public function getIndentationBefore(int $pos) : int {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this->indentMap[$pos];
    }

    /**
     * Get the code corresponding to a token offset range, optionally adjusted for indentation.
     *
<<<<<<< HEAD
     * @param int $from Token start position (inclusive)
     * @param int $to Token end position (exclusive)
=======
     * @param int $from   Token start position (inclusive)
     * @param int $to     Token end position (exclusive)
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     * @param int $indent By how much the code should be indented (can be negative as well)
     *
     * @return string Code corresponding to token range, adjusted for indentation
     */
<<<<<<< HEAD
    public function getTokenCode(int $from, int $to, int $indent): string {
=======
    public function getTokenCode(int $from, int $to, int $indent) : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $tokens = $this->tokens;
        $result = '';
        for ($pos = $from; $pos < $to; $pos++) {
            $token = $tokens[$pos];
<<<<<<< HEAD
            $id = $token->id;
            $text = $token->text;
            if ($id === \T_CONSTANT_ENCAPSED_STRING || $id === \T_ENCAPSED_AND_WHITESPACE) {
                $result .= $text;
            } else {
                // TODO Handle non-space indentation
                if ($indent < 0) {
                    $result .= str_replace("\n" . str_repeat(" ", -$indent), "\n", $text);
                } elseif ($indent > 0) {
                    $result .= str_replace("\n", "\n" . str_repeat(" ", $indent), $text);
                } else {
                    $result .= $text;
                }
=======
            if (\is_array($token)) {
                $type = $token[0];
                $content = $token[1];
                if ($type === \T_CONSTANT_ENCAPSED_STRING || $type === \T_ENCAPSED_AND_WHITESPACE) {
                    $result .= $content;
                } else {
                    // TODO Handle non-space indentation
                    if ($indent < 0) {
                        $result .= str_replace("\n" . str_repeat(" ", -$indent), "\n", $content);
                    } elseif ($indent > 0) {
                        $result .= str_replace("\n", "\n" . str_repeat(" ", $indent), $content);
                    } else {
                        $result .= $content;
                    }
                }
            } else {
                $result .= $token;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            }
        }
        return $result;
    }

    /**
     * Precalculate the indentation at every token position.
     *
     * @return int[] Token position to indentation map
     */
<<<<<<< HEAD
    private function calcIndentMap(): array {
        $indentMap = [];
        $indent = 0;
        foreach ($this->tokens as $i => $token) {
            $indentMap[] = $indent;

            if ($token->id === \T_WHITESPACE) {
                $content = $token->text;
                $newlinePos = \strrpos($content, "\n");
                if (false !== $newlinePos) {
                    $indent = \strlen($content) - $newlinePos - 1;
                } elseif ($i === 1 && $this->tokens[0]->id === \T_OPEN_TAG &&
                          $this->tokens[0]->text[\strlen($this->tokens[0]->text) - 1] === "\n") {
                    // Special case: Newline at the end of opening tag followed by whitespace.
                    $indent = \strlen($content);
=======
    private function calcIndentMap() {
        $indentMap = [];
        $indent = 0;
        foreach ($this->tokens as $token) {
            $indentMap[] = $indent;

            if ($token[0] === \T_WHITESPACE) {
                $content = $token[1];
                $newlinePos = \strrpos($content, "\n");
                if (false !== $newlinePos) {
                    $indent = \strlen($content) - $newlinePos - 1;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                }
            }
        }

        // Add a sentinel for one past end of the file
        $indentMap[] = $indent;

        return $indentMap;
    }
}
