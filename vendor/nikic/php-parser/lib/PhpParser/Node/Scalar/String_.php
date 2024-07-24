<?php declare(strict_types=1);

namespace PhpParser\Node\Scalar;

use PhpParser\Error;
use PhpParser\Node\Scalar;

<<<<<<< HEAD
class String_ extends Scalar {
    /* For use in "kind" attribute */
    public const KIND_SINGLE_QUOTED = 1;
    public const KIND_DOUBLE_QUOTED = 2;
    public const KIND_HEREDOC = 3;
    public const KIND_NOWDOC = 4;

    /** @var string String value */
    public string $value;

    /** @var array<string, string> Escaped character to its decoded value */
    protected static array $replacements = [
=======
class String_ extends Scalar
{
    /* For use in "kind" attribute */
    const KIND_SINGLE_QUOTED = 1;
    const KIND_DOUBLE_QUOTED = 2;
    const KIND_HEREDOC = 3;
    const KIND_NOWDOC = 4;

    /** @var string String value */
    public $value;

    protected static $replacements = [
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        '\\' => '\\',
        '$'  =>  '$',
        'n'  => "\n",
        'r'  => "\r",
        't'  => "\t",
        'f'  => "\f",
        'v'  => "\v",
        'e'  => "\x1B",
    ];

    /**
     * Constructs a string scalar node.
     *
<<<<<<< HEAD
     * @param string $value Value of the string
     * @param array<string, mixed> $attributes Additional attributes
=======
     * @param string $value      Value of the string
     * @param array  $attributes Additional attributes
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function __construct(string $value, array $attributes = []) {
        $this->attributes = $attributes;
        $this->value = $value;
    }

<<<<<<< HEAD
    public function getSubNodeNames(): array {
=======
    public function getSubNodeNames() : array {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return ['value'];
    }

    /**
<<<<<<< HEAD
     * @param array<string, mixed> $attributes
     * @param bool $parseUnicodeEscape Whether to parse PHP 7 \u escapes
     */
    public static function fromString(string $str, array $attributes = [], bool $parseUnicodeEscape = true): self {
=======
     * @param bool $parseUnicodeEscape Whether to parse PHP 7 \u escapes
     */
    public static function fromString(string $str, array $attributes = [], bool $parseUnicodeEscape = true): self
    {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $attributes['kind'] = ($str[0] === "'" || ($str[1] === "'" && ($str[0] === 'b' || $str[0] === 'B')))
            ? Scalar\String_::KIND_SINGLE_QUOTED
            : Scalar\String_::KIND_DOUBLE_QUOTED;

        $attributes['rawValue'] = $str;

        $string = self::parse($str, $parseUnicodeEscape);

        return new self($string, $attributes);
    }

    /**
     * @internal
     *
     * Parses a string token.
     *
     * @param string $str String token content
     * @param bool $parseUnicodeEscape Whether to parse PHP 7 \u escapes
     *
     * @return string The parsed string
     */
<<<<<<< HEAD
    public static function parse(string $str, bool $parseUnicodeEscape = true): string {
=======
    public static function parse(string $str, bool $parseUnicodeEscape = true) : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $bLength = 0;
        if ('b' === $str[0] || 'B' === $str[0]) {
            $bLength = 1;
        }

        if ('\'' === $str[$bLength]) {
            return str_replace(
                ['\\\\', '\\\''],
                ['\\', '\''],
                substr($str, $bLength + 1, -1)
            );
        } else {
            return self::parseEscapeSequences(
                substr($str, $bLength + 1, -1), '"', $parseUnicodeEscape
            );
        }
    }

    /**
     * @internal
     *
     * Parses escape sequences in strings (all string types apart from single quoted).
     *
<<<<<<< HEAD
     * @param string $str String without quotes
=======
     * @param string      $str   String without quotes
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     * @param null|string $quote Quote type
     * @param bool $parseUnicodeEscape Whether to parse PHP 7 \u escapes
     *
     * @return string String with escape sequences parsed
     */
<<<<<<< HEAD
    public static function parseEscapeSequences(string $str, ?string $quote, bool $parseUnicodeEscape = true): string {
=======
    public static function parseEscapeSequences(string $str, $quote, bool $parseUnicodeEscape = true) : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        if (null !== $quote) {
            $str = str_replace('\\' . $quote, $quote, $str);
        }

        $extra = '';
        if ($parseUnicodeEscape) {
            $extra = '|u\{([0-9a-fA-F]+)\}';
        }

        return preg_replace_callback(
            '~\\\\([\\\\$nrtfve]|[xX][0-9a-fA-F]{1,2}|[0-7]{1,3}' . $extra . ')~',
<<<<<<< HEAD
            function ($matches) {
=======
            function($matches) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                $str = $matches[1];

                if (isset(self::$replacements[$str])) {
                    return self::$replacements[$str];
<<<<<<< HEAD
                }
                if ('x' === $str[0] || 'X' === $str[0]) {
                    return chr(hexdec(substr($str, 1)));
                }
                if ('u' === $str[0]) {
                    $dec = hexdec($matches[2]);
                    // If it overflowed to float, treat as INT_MAX, it will throw an error anyway.
                    return self::codePointToUtf8(\is_int($dec) ? $dec : \PHP_INT_MAX);
=======
                } elseif ('x' === $str[0] || 'X' === $str[0]) {
                    return chr(hexdec(substr($str, 1)));
                } elseif ('u' === $str[0]) {
                    return self::codePointToUtf8(hexdec($matches[2]));
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                } else {
                    return chr(octdec($str));
                }
            },
            $str
        );
    }

    /**
     * Converts a Unicode code point to its UTF-8 encoded representation.
     *
     * @param int $num Code point
     *
     * @return string UTF-8 representation of code point
     */
<<<<<<< HEAD
    private static function codePointToUtf8(int $num): string {
=======
    private static function codePointToUtf8(int $num) : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        if ($num <= 0x7F) {
            return chr($num);
        }
        if ($num <= 0x7FF) {
<<<<<<< HEAD
            return chr(($num >> 6) + 0xC0) . chr(($num & 0x3F) + 0x80);
        }
        if ($num <= 0xFFFF) {
            return chr(($num >> 12) + 0xE0) . chr((($num >> 6) & 0x3F) + 0x80) . chr(($num & 0x3F) + 0x80);
        }
        if ($num <= 0x1FFFFF) {
            return chr(($num >> 18) + 0xF0) . chr((($num >> 12) & 0x3F) + 0x80)
                 . chr((($num >> 6) & 0x3F) + 0x80) . chr(($num & 0x3F) + 0x80);
=======
            return chr(($num>>6) + 0xC0) . chr(($num&0x3F) + 0x80);
        }
        if ($num <= 0xFFFF) {
            return chr(($num>>12) + 0xE0) . chr((($num>>6)&0x3F) + 0x80) . chr(($num&0x3F) + 0x80);
        }
        if ($num <= 0x1FFFFF) {
            return chr(($num>>18) + 0xF0) . chr((($num>>12)&0x3F) + 0x80)
                 . chr((($num>>6)&0x3F) + 0x80) . chr(($num&0x3F) + 0x80);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        }
        throw new Error('Invalid UTF-8 codepoint escape sequence: Codepoint too large');
    }

<<<<<<< HEAD
    public function getType(): string {
=======
    public function getType() : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return 'Scalar_String';
    }
}
