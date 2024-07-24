<?php declare(strict_types=1);

namespace PhpParser\Lexer\TokenEmulator;

<<<<<<< HEAD
use PhpParser\PhpVersion;
use PhpParser\Token;

/** @internal */
abstract class TokenEmulator {
    abstract public function getPhpVersion(): PhpVersion;
=======
/** @internal */
abstract class TokenEmulator
{
    abstract public function getPhpVersion(): string;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    abstract public function isEmulationNeeded(string $code): bool;

    /**
<<<<<<< HEAD
     * @param Token[] $tokens Original tokens
     * @return Token[] Modified Tokens
=======
     * @return array Modified Tokens
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    abstract public function emulate(string $code, array $tokens): array;

    /**
<<<<<<< HEAD
     * @param Token[] $tokens Original tokens
     * @return Token[] Modified Tokens
     */
    abstract public function reverseEmulate(string $code, array $tokens): array;

    /** @param array{int, string, string}[] $patches */
=======
     * @return array Modified Tokens
     */
    abstract public function reverseEmulate(string $code, array $tokens): array;

>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function preprocessCode(string $code, array &$patches): string {
        return $code;
    }
}
