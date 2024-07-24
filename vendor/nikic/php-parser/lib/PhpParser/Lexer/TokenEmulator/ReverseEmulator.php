<?php declare(strict_types=1);

namespace PhpParser\Lexer\TokenEmulator;

<<<<<<< HEAD
use PhpParser\PhpVersion;

/**
 * Reverses emulation direction of the inner emulator.
 */
final class ReverseEmulator extends TokenEmulator {
    /** @var TokenEmulator Inner emulator */
    private TokenEmulator $emulator;
=======
/**
 * Reverses emulation direction of the inner emulator.
 */
final class ReverseEmulator extends TokenEmulator
{
    /** @var TokenEmulator Inner emulator */
    private $emulator;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    public function __construct(TokenEmulator $emulator) {
        $this->emulator = $emulator;
    }

<<<<<<< HEAD
    public function getPhpVersion(): PhpVersion {
=======
    public function getPhpVersion(): string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return $this->emulator->getPhpVersion();
    }

    public function isEmulationNeeded(string $code): bool {
        return $this->emulator->isEmulationNeeded($code);
    }

    public function emulate(string $code, array $tokens): array {
        return $this->emulator->reverseEmulate($code, $tokens);
    }

    public function reverseEmulate(string $code, array $tokens): array {
        return $this->emulator->emulate($code, $tokens);
    }

    public function preprocessCode(string $code, array &$patches): string {
        return $code;
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
