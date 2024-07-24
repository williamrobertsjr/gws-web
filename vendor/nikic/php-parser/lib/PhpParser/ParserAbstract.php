<?php declare(strict_types=1);

namespace PhpParser;

/*
 * This parser is based on a skeleton written by Moriyoshi Koizumi, which in
 * turn is based on work by Masato Bito.
 */
<<<<<<< HEAD

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Cast\Double;
use PhpParser\Node\Identifier;
use PhpParser\Node\InterpolatedStringPart;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\InterpolatedString;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt;
=======
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Cast\Double;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\Encapsed;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\Enum_;
use PhpParser\Node\Stmt\Interface_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\TryCatch;
<<<<<<< HEAD
use PhpParser\Node\UseItem;
use PhpParser\NodeVisitor\CommentAnnotatingVisitor;

abstract class ParserAbstract implements Parser {
    private const SYMBOL_NONE = -1;

    /** @var Lexer Lexer that is used when parsing */
    protected Lexer $lexer;
    /** @var PhpVersion PHP version to target on a best-effort basis */
    protected PhpVersion $phpVersion;
=======
use PhpParser\Node\Stmt\UseUse;
use PhpParser\Node\VarLikeIdentifier;

abstract class ParserAbstract implements Parser
{
    const SYMBOL_NONE = -1;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /*
     * The following members will be filled with generated parsing data:
     */

    /** @var int Size of $tokenToSymbol map */
<<<<<<< HEAD
    protected int $tokenToSymbolMapSize;
    /** @var int Size of $action table */
    protected int $actionTableSize;
    /** @var int Size of $goto table */
    protected int $gotoTableSize;

    /** @var int Symbol number signifying an invalid token */
    protected int $invalidSymbol;
    /** @var int Symbol number of error recovery token */
    protected int $errorSymbol;
    /** @var int Action number signifying default action */
    protected int $defaultAction;
    /** @var int Rule number signifying that an unexpected token was encountered */
    protected int $unexpectedTokenRule;

    protected int $YY2TBLSTATE;
    /** @var int Number of non-leaf states */
    protected int $numNonLeafStates;

    /** @var int[] Map of PHP token IDs to internal symbols */
    protected array $phpTokenToSymbol;
    /** @var array<int, bool> Map of PHP token IDs to drop */
    protected array $dropTokens;
    /** @var int[] Map of external symbols (static::T_*) to internal symbols */
    protected array $tokenToSymbol;
    /** @var string[] Map of symbols to their names */
    protected array $symbolToName;
    /** @var array<int, string> Names of the production rules (only necessary for debugging) */
    protected array $productions;
=======
    protected $tokenToSymbolMapSize;
    /** @var int Size of $action table */
    protected $actionTableSize;
    /** @var int Size of $goto table */
    protected $gotoTableSize;

    /** @var int Symbol number signifying an invalid token */
    protected $invalidSymbol;
    /** @var int Symbol number of error recovery token */
    protected $errorSymbol;
    /** @var int Action number signifying default action */
    protected $defaultAction;
    /** @var int Rule number signifying that an unexpected token was encountered */
    protected $unexpectedTokenRule;

    protected $YY2TBLSTATE;
    /** @var int Number of non-leaf states */
    protected $numNonLeafStates;

    /** @var int[] Map of lexer tokens to internal symbols */
    protected $tokenToSymbol;
    /** @var string[] Map of symbols to their names */
    protected $symbolToName;
    /** @var array Names of the production rules (only necessary for debugging) */
    protected $productions;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /** @var int[] Map of states to a displacement into the $action table. The corresponding action for this
     *             state/symbol pair is $action[$actionBase[$state] + $symbol]. If $actionBase[$state] is 0, the
     *             action is defaulted, i.e. $actionDefault[$state] should be used instead. */
<<<<<<< HEAD
    protected array $actionBase;
    /** @var int[] Table of actions. Indexed according to $actionBase comment. */
    protected array $action;
    /** @var int[] Table indexed analogously to $action. If $actionCheck[$actionBase[$state] + $symbol] != $symbol
     *             then the action is defaulted, i.e. $actionDefault[$state] should be used instead. */
    protected array $actionCheck;
    /** @var int[] Map of states to their default action */
    protected array $actionDefault;
    /** @var callable[] Semantic action callbacks */
    protected array $reduceCallbacks;

    /** @var int[] Map of non-terminals to a displacement into the $goto table. The corresponding goto state for this
     *             non-terminal/state pair is $goto[$gotoBase[$nonTerminal] + $state] (unless defaulted) */
    protected array $gotoBase;
    /** @var int[] Table of states to goto after reduction. Indexed according to $gotoBase comment. */
    protected array $goto;
    /** @var int[] Table indexed analogously to $goto. If $gotoCheck[$gotoBase[$nonTerminal] + $state] != $nonTerminal
     *             then the goto state is defaulted, i.e. $gotoDefault[$nonTerminal] should be used. */
    protected array $gotoCheck;
    /** @var int[] Map of non-terminals to the default state to goto after their reduction */
    protected array $gotoDefault;

    /** @var int[] Map of rules to the non-terminal on their left-hand side, i.e. the non-terminal to use for
     *             determining the state to goto after reduction. */
    protected array $ruleToNonTerminal;
    /** @var int[] Map of rules to the length of their right-hand side, which is the number of elements that have to
     *             be popped from the stack(s) on reduction. */
    protected array $ruleToLength;
=======
    protected $actionBase;
    /** @var int[] Table of actions. Indexed according to $actionBase comment. */
    protected $action;
    /** @var int[] Table indexed analogously to $action. If $actionCheck[$actionBase[$state] + $symbol] != $symbol
     *             then the action is defaulted, i.e. $actionDefault[$state] should be used instead. */
    protected $actionCheck;
    /** @var int[] Map of states to their default action */
    protected $actionDefault;
    /** @var callable[] Semantic action callbacks */
    protected $reduceCallbacks;

    /** @var int[] Map of non-terminals to a displacement into the $goto table. The corresponding goto state for this
     *             non-terminal/state pair is $goto[$gotoBase[$nonTerminal] + $state] (unless defaulted) */
    protected $gotoBase;
    /** @var int[] Table of states to goto after reduction. Indexed according to $gotoBase comment. */
    protected $goto;
    /** @var int[] Table indexed analogously to $goto. If $gotoCheck[$gotoBase[$nonTerminal] + $state] != $nonTerminal
     *             then the goto state is defaulted, i.e. $gotoDefault[$nonTerminal] should be used. */
    protected $gotoCheck;
    /** @var int[] Map of non-terminals to the default state to goto after their reduction */
    protected $gotoDefault;

    /** @var int[] Map of rules to the non-terminal on their left-hand side, i.e. the non-terminal to use for
     *             determining the state to goto after reduction. */
    protected $ruleToNonTerminal;
    /** @var int[] Map of rules to the length of their right-hand side, which is the number of elements that have to
     *             be popped from the stack(s) on reduction. */
    protected $ruleToLength;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /*
     * The following members are part of the parser state:
     */

<<<<<<< HEAD
    /** @var mixed Temporary value containing the result of last semantic action (reduction) */
    protected $semValue;
    /** @var mixed[] Semantic value stack (contains values of tokens and semantic action results) */
    protected array $semStack;
    /** @var int[] Token start position stack */
    protected array $tokenStartStack;
    /** @var int[] Token end position stack */
    protected array $tokenEndStack;

    /** @var ErrorHandler Error handler */
    protected ErrorHandler $errorHandler;
    /** @var int Error state, used to avoid error floods */
    protected int $errorState;

    /** @var \SplObjectStorage<Array_, null>|null Array nodes created during parsing, for postprocessing of empty elements. */
    protected ?\SplObjectStorage $createdArrays;

    /** @var Token[] Tokens for the current parse */
    protected array $tokens;
    /** @var int Current position in token array */
    protected int $tokenPos;
=======
    /** @var Lexer Lexer that is used when parsing */
    protected $lexer;
    /** @var mixed Temporary value containing the result of last semantic action (reduction) */
    protected $semValue;
    /** @var array Semantic value stack (contains values of tokens and semantic action results) */
    protected $semStack;
    /** @var array[] Start attribute stack */
    protected $startAttributeStack;
    /** @var array[] End attribute stack */
    protected $endAttributeStack;
    /** @var array End attributes of last *shifted* token */
    protected $endAttributes;
    /** @var array Start attributes of last *read* token */
    protected $lookaheadStartAttributes;

    /** @var ErrorHandler Error handler */
    protected $errorHandler;
    /** @var int Error state, used to avoid error floods */
    protected $errorState;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Initialize $reduceCallbacks map.
     */
<<<<<<< HEAD
    abstract protected function initReduceCallbacks(): void;
=======
    abstract protected function initReduceCallbacks();
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

    /**
     * Creates a parser instance.
     *
<<<<<<< HEAD
     * Options:
     *  * phpVersion: ?PhpVersion,
     *
     * @param Lexer $lexer A lexer
     * @param PhpVersion $phpVersion PHP version to target, defaults to latest supported. This
     *                               option is best-effort: Even if specified, parsing will generally assume the latest
     *                               supported version and only adjust behavior in minor ways, for example by omitting
     *                               errors in older versions and interpreting type hints as a name or identifier depending
     *                               on version.
     */
    public function __construct(Lexer $lexer, ?PhpVersion $phpVersion = null) {
        $this->lexer = $lexer;
        $this->phpVersion = $phpVersion ?? PhpVersion::getNewestSupported();

        $this->initReduceCallbacks();
        $this->phpTokenToSymbol = $this->createTokenMap();
        $this->dropTokens = array_fill_keys(
            [\T_WHITESPACE, \T_OPEN_TAG, \T_COMMENT, \T_DOC_COMMENT, \T_BAD_CHARACTER], true
        );
=======
     * Options: Currently none.
     *
     * @param Lexer $lexer A lexer
     * @param array $options Options array.
     */
    public function __construct(Lexer $lexer, array $options = []) {
        $this->lexer = $lexer;

        if (isset($options['throwOnError'])) {
            throw new \LogicException(
                '"throwOnError" is no longer supported, use "errorHandler" instead');
        }

        $this->initReduceCallbacks();
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    /**
     * Parses PHP code into a node tree.
     *
     * If a non-throwing error handler is used, the parser will continue parsing after an error
     * occurred and attempt to build a partial AST.
     *
     * @param string $code The source code to parse
     * @param ErrorHandler|null $errorHandler Error handler to use for lexer/parser errors, defaults
     *                                        to ErrorHandler\Throwing.
     *
     * @return Node\Stmt[]|null Array of statements (or null non-throwing error handler is used and
     *                          the parser was unable to recover from an error).
     */
<<<<<<< HEAD
    public function parse(string $code, ?ErrorHandler $errorHandler = null): ?array {
        $this->errorHandler = $errorHandler ?: new ErrorHandler\Throwing();
        $this->createdArrays = new \SplObjectStorage();

        $this->tokens = $this->lexer->tokenize($code, $this->errorHandler);
        $result = $this->doParse();

        // Report errors for any empty elements used inside arrays. This is delayed until after the main parse,
        // because we don't know a priori whether a given array expression will be used in a destructuring context
        // or not.
        foreach ($this->createdArrays as $node) {
            foreach ($node->items as $item) {
                if ($item->value instanceof Expr\Error) {
                    $this->errorHandler->handleError(
                        new Error('Cannot use empty array elements in arrays', $item->getAttributes()));
                }
            }
        }

        // Clear out some of the interior state, so we don't hold onto unnecessary
        // memory between uses of the parser
        $this->tokenStartStack = [];
        $this->tokenEndStack = [];
        $this->semStack = [];
        $this->semValue = null;
        $this->createdArrays = null;

        if ($result !== null) {
            $traverser = new NodeTraverser(new CommentAnnotatingVisitor($this->tokens));
            $traverser->traverse($result);
        }
=======
    public function parse(string $code, ErrorHandler $errorHandler = null) {
        $this->errorHandler = $errorHandler ?: new ErrorHandler\Throwing;

        $this->lexer->startLexing($code, $this->errorHandler);
        $result = $this->doParse();

        // Clear out some of the interior state, so we don't hold onto unnecessary
        // memory between uses of the parser
        $this->startAttributeStack = [];
        $this->endAttributeStack = [];
        $this->semStack = [];
        $this->semValue = null;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

        return $result;
    }

<<<<<<< HEAD
    public function getTokens(): array {
        return $this->tokens;
    }

    /** @return Stmt[]|null */
    protected function doParse(): ?array {
        // We start off with no lookahead-token
        $symbol = self::SYMBOL_NONE;
        $tokenValue = null;
        $this->tokenPos = -1;

        // Keep stack of start and end attributes
        $this->tokenStartStack = [];
        $this->tokenEndStack = [0];
=======
    protected function doParse() {
        // We start off with no lookahead-token
        $symbol = self::SYMBOL_NONE;

        // The attributes for a node are taken from the first and last token of the node.
        // From the first token only the startAttributes are taken and from the last only
        // the endAttributes. Both are merged using the array union operator (+).
        $startAttributes = [];
        $endAttributes = [];
        $this->endAttributes = $endAttributes;

        // Keep stack of start and end attributes
        $this->startAttributeStack = [];
        $this->endAttributeStack = [$endAttributes];
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

        // Start off in the initial state and keep a stack of previous states
        $state = 0;
        $stateStack = [$state];

        // Semantic value stack (contains values of tokens and semantic action results)
        $this->semStack = [];

        // Current position in the stack(s)
        $stackPos = 0;

        $this->errorState = 0;

        for (;;) {
            //$this->traceNewState($state, $symbol);

            if ($this->actionBase[$state] === 0) {
                $rule = $this->actionDefault[$state];
            } else {
                if ($symbol === self::SYMBOL_NONE) {
<<<<<<< HEAD
                    do {
                        $token = $this->tokens[++$this->tokenPos];
                        $tokenId = $token->id;
                    } while (isset($this->dropTokens[$tokenId]));

                    // Map the lexer token id to the internally used symbols.
                    $tokenValue = $token->text;
                    if (!isset($this->phpTokenToSymbol[$tokenId])) {
=======
                    // Fetch the next token id from the lexer and fetch additional info by-ref.
                    // The end attributes are fetched into a temporary variable and only set once the token is really
                    // shifted (not during read). Otherwise you would sometimes get off-by-one errors, when a rule is
                    // reduced after a token was read but not yet shifted.
                    $tokenId = $this->lexer->getNextToken($tokenValue, $startAttributes, $endAttributes);

                    // map the lexer token id to the internally used symbols
                    $symbol = $tokenId >= 0 && $tokenId < $this->tokenToSymbolMapSize
                        ? $this->tokenToSymbol[$tokenId]
                        : $this->invalidSymbol;

                    if ($symbol === $this->invalidSymbol) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                        throw new \RangeException(sprintf(
                            'The lexer returned an invalid token (id=%d, value=%s)',
                            $tokenId, $tokenValue
                        ));
                    }
<<<<<<< HEAD
                    $symbol = $this->phpTokenToSymbol[$tokenId];
=======

                    // Allow productions to access the start attributes of the lookahead token.
                    $this->lookaheadStartAttributes = $startAttributes;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

                    //$this->traceRead($symbol);
                }

                $idx = $this->actionBase[$state] + $symbol;
                if ((($idx >= 0 && $idx < $this->actionTableSize && $this->actionCheck[$idx] === $symbol)
                     || ($state < $this->YY2TBLSTATE
                         && ($idx = $this->actionBase[$state + $this->numNonLeafStates] + $symbol) >= 0
                         && $idx < $this->actionTableSize && $this->actionCheck[$idx] === $symbol))
                    && ($action = $this->action[$idx]) !== $this->defaultAction) {
                    /*
                     * >= numNonLeafStates: shift and reduce
                     * > 0: shift
                     * = 0: accept
                     * < 0: reduce
                     * = -YYUNEXPECTED: error
                     */
                    if ($action > 0) {
                        /* shift */
                        //$this->traceShift($symbol);

                        ++$stackPos;
                        $stateStack[$stackPos] = $state = $action;
                        $this->semStack[$stackPos] = $tokenValue;
<<<<<<< HEAD
                        $this->tokenStartStack[$stackPos] = $this->tokenPos;
                        $this->tokenEndStack[$stackPos] = $this->tokenPos;
=======
                        $this->startAttributeStack[$stackPos] = $startAttributes;
                        $this->endAttributeStack[$stackPos] = $endAttributes;
                        $this->endAttributes = $endAttributes;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                        $symbol = self::SYMBOL_NONE;

                        if ($this->errorState) {
                            --$this->errorState;
                        }

                        if ($action < $this->numNonLeafStates) {
                            continue;
                        }

                        /* $yyn >= numNonLeafStates means shift-and-reduce */
                        $rule = $action - $this->numNonLeafStates;
                    } else {
                        $rule = -$action;
                    }
                } else {
                    $rule = $this->actionDefault[$state];
                }
            }

            for (;;) {
                if ($rule === 0) {
                    /* accept */
                    //$this->traceAccept();
                    return $this->semValue;
<<<<<<< HEAD
                }
                if ($rule !== $this->unexpectedTokenRule) {
                    /* reduce */
                    //$this->traceReduce($rule);

                    $ruleLength = $this->ruleToLength[$rule];
                    try {
                        $callback = $this->reduceCallbacks[$rule];
                        if ($callback !== null) {
                            $callback($this, $stackPos);
                        } elseif ($ruleLength > 0) {
                            $this->semValue = $this->semStack[$stackPos - $ruleLength + 1];
                        }
                    } catch (Error $e) {
                        if (-1 === $e->getStartLine()) {
                            $e->setStartLine($this->tokens[$this->tokenPos]->line);
=======
                } elseif ($rule !== $this->unexpectedTokenRule) {
                    /* reduce */
                    //$this->traceReduce($rule);

                    try {
                        $this->reduceCallbacks[$rule]($stackPos);
                    } catch (Error $e) {
                        if (-1 === $e->getStartLine() && isset($startAttributes['startLine'])) {
                            $e->setStartLine($startAttributes['startLine']);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                        }

                        $this->emitError($e);
                        // Can't recover from this type of error
                        return null;
                    }

                    /* Goto - shift nonterminal */
<<<<<<< HEAD
                    $lastTokenEnd = $this->tokenEndStack[$stackPos];
=======
                    $lastEndAttributes = $this->endAttributeStack[$stackPos];
                    $ruleLength = $this->ruleToLength[$rule];
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                    $stackPos -= $ruleLength;
                    $nonTerminal = $this->ruleToNonTerminal[$rule];
                    $idx = $this->gotoBase[$nonTerminal] + $stateStack[$stackPos];
                    if ($idx >= 0 && $idx < $this->gotoTableSize && $this->gotoCheck[$idx] === $nonTerminal) {
                        $state = $this->goto[$idx];
                    } else {
                        $state = $this->gotoDefault[$nonTerminal];
                    }

                    ++$stackPos;
                    $stateStack[$stackPos]     = $state;
                    $this->semStack[$stackPos] = $this->semValue;
<<<<<<< HEAD
                    $this->tokenEndStack[$stackPos] = $lastTokenEnd;
                    if ($ruleLength === 0) {
                        // Empty productions use the start attributes of the lookahead token.
                        $this->tokenStartStack[$stackPos] = $this->tokenPos;
=======
                    $this->endAttributeStack[$stackPos] = $lastEndAttributes;
                    if ($ruleLength === 0) {
                        // Empty productions use the start attributes of the lookahead token.
                        $this->startAttributeStack[$stackPos] = $this->lookaheadStartAttributes;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                    }
                } else {
                    /* error */
                    switch ($this->errorState) {
                        case 0:
                            $msg = $this->getErrorMessage($symbol, $state);
<<<<<<< HEAD
                            $this->emitError(new Error($msg, $this->getAttributesForToken($this->tokenPos)));
                            // Break missing intentionally
                            // no break
=======
                            $this->emitError(new Error($msg, $startAttributes + $endAttributes));
                            // Break missing intentionally
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                        case 1:
                        case 2:
                            $this->errorState = 3;

                            // Pop until error-expecting state uncovered
                            while (!(
                                (($idx = $this->actionBase[$state] + $this->errorSymbol) >= 0
                                    && $idx < $this->actionTableSize && $this->actionCheck[$idx] === $this->errorSymbol)
                                || ($state < $this->YY2TBLSTATE
                                    && ($idx = $this->actionBase[$state + $this->numNonLeafStates] + $this->errorSymbol) >= 0
                                    && $idx < $this->actionTableSize && $this->actionCheck[$idx] === $this->errorSymbol)
                            ) || ($action = $this->action[$idx]) === $this->defaultAction) { // Not totally sure about this
                                if ($stackPos <= 0) {
                                    // Could not recover from error
                                    return null;
                                }
                                $state = $stateStack[--$stackPos];
                                //$this->tracePop($state);
                            }

                            //$this->traceShift($this->errorSymbol);
                            ++$stackPos;
                            $stateStack[$stackPos] = $state = $action;

                            // We treat the error symbol as being empty, so we reset the end attributes
                            // to the end attributes of the last non-error symbol
<<<<<<< HEAD
                            $this->tokenStartStack[$stackPos] = $this->tokenPos;
                            $this->tokenEndStack[$stackPos] = $this->tokenEndStack[$stackPos - 1];
=======
                            $this->startAttributeStack[$stackPos] = $this->lookaheadStartAttributes;
                            $this->endAttributeStack[$stackPos] = $this->endAttributeStack[$stackPos - 1];
                            $this->endAttributes = $this->endAttributeStack[$stackPos - 1];
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                            break;

                        case 3:
                            if ($symbol === 0) {
                                // Reached EOF without recovering from error
                                return null;
                            }

                            //$this->traceDiscard($symbol);
                            $symbol = self::SYMBOL_NONE;
                            break 2;
                    }
                }

                if ($state < $this->numNonLeafStates) {
                    break;
                }

                /* >= numNonLeafStates means shift-and-reduce */
                $rule = $state - $this->numNonLeafStates;
            }
        }

        throw new \RuntimeException('Reached end of parser loop');
    }

<<<<<<< HEAD
    protected function emitError(Error $error): void {
=======
    protected function emitError(Error $error) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $this->errorHandler->handleError($error);
    }

    /**
     * Format error message including expected tokens.
     *
     * @param int $symbol Unexpected symbol
<<<<<<< HEAD
     * @param int $state State at time of error
     *
     * @return string Formatted error message
     */
    protected function getErrorMessage(int $symbol, int $state): string {
=======
     * @param int $state  State at time of error
     *
     * @return string Formatted error message
     */
    protected function getErrorMessage(int $symbol, int $state) : string {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $expectedString = '';
        if ($expected = $this->getExpectedTokens($state)) {
            $expectedString = ', expecting ' . implode(' or ', $expected);
        }

        return 'Syntax error, unexpected ' . $this->symbolToName[$symbol] . $expectedString;
    }

    /**
     * Get limited number of expected tokens in given state.
     *
     * @param int $state State
     *
     * @return string[] Expected tokens. If too many, an empty array is returned.
     */
<<<<<<< HEAD
    protected function getExpectedTokens(int $state): array {
=======
    protected function getExpectedTokens(int $state) : array {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $expected = [];

        $base = $this->actionBase[$state];
        foreach ($this->symbolToName as $symbol => $name) {
            $idx = $base + $symbol;
            if ($idx >= 0 && $idx < $this->actionTableSize && $this->actionCheck[$idx] === $symbol
                || $state < $this->YY2TBLSTATE
                && ($idx = $this->actionBase[$state + $this->numNonLeafStates] + $symbol) >= 0
                && $idx < $this->actionTableSize && $this->actionCheck[$idx] === $symbol
            ) {
                if ($this->action[$idx] !== $this->unexpectedTokenRule
                    && $this->action[$idx] !== $this->defaultAction
                    && $symbol !== $this->errorSymbol
                ) {
                    if (count($expected) === 4) {
                        /* Too many expected tokens */
                        return [];
                    }

                    $expected[] = $name;
                }
            }
        }

        return $expected;
    }

<<<<<<< HEAD
    /**
     * Get attributes for a node with the given start and end token positions.
     *
     * @param int $tokenStartPos Token position the node starts at
     * @param int $tokenEndPos Token position the node ends at
     * @return array<string, mixed> Attributes
     */
    protected function getAttributes(int $tokenStartPos, int $tokenEndPos): array {
        $startToken = $this->tokens[$tokenStartPos];
        $afterEndToken = $this->tokens[$tokenEndPos + 1];
        return [
            'startLine' => $startToken->line,
            'startTokenPos' => $tokenStartPos,
            'startFilePos' => $startToken->pos,
            'endLine' => $afterEndToken->line,
            'endTokenPos' => $tokenEndPos,
            'endFilePos' => $afterEndToken->pos - 1,
        ];
    }

    /**
     * Get attributes for a single token at the given token position.
     *
     * @return array<string, mixed> Attributes
     */
    protected function getAttributesForToken(int $tokenPos): array {
        if ($tokenPos < \count($this->tokens) - 1) {
            return $this->getAttributes($tokenPos, $tokenPos);
        }

        // Get attributes for the sentinel token.
        $token = $this->tokens[$tokenPos];
        return [
            'startLine' => $token->line,
            'startTokenPos' => $tokenPos,
            'startFilePos' => $token->pos,
            'endLine' => $token->line,
            'endTokenPos' => $tokenPos,
            'endFilePos' => $token->pos,
        ];
    }

=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    /*
     * Tracing functions used for debugging the parser.
     */

    /*
<<<<<<< HEAD
    protected function traceNewState($state, $symbol): void {
=======
    protected function traceNewState($state, $symbol) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        echo '% State ' . $state
            . ', Lookahead ' . ($symbol == self::SYMBOL_NONE ? '--none--' : $this->symbolToName[$symbol]) . "\n";
    }

<<<<<<< HEAD
    protected function traceRead($symbol): void {
        echo '% Reading ' . $this->symbolToName[$symbol] . "\n";
    }

    protected function traceShift($symbol): void {
        echo '% Shift ' . $this->symbolToName[$symbol] . "\n";
    }

    protected function traceAccept(): void {
        echo "% Accepted.\n";
    }

    protected function traceReduce($n): void {
        echo '% Reduce by (' . $n . ') ' . $this->productions[$n] . "\n";
    }

    protected function tracePop($state): void {
        echo '% Recovering, uncovered state ' . $state . "\n";
    }

    protected function traceDiscard($symbol): void {
=======
    protected function traceRead($symbol) {
        echo '% Reading ' . $this->symbolToName[$symbol] . "\n";
    }

    protected function traceShift($symbol) {
        echo '% Shift ' . $this->symbolToName[$symbol] . "\n";
    }

    protected function traceAccept() {
        echo "% Accepted.\n";
    }

    protected function traceReduce($n) {
        echo '% Reduce by (' . $n . ') ' . $this->productions[$n] . "\n";
    }

    protected function tracePop($state) {
        echo '% Recovering, uncovered state ' . $state . "\n";
    }

    protected function traceDiscard($symbol) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        echo '% Discard ' . $this->symbolToName[$symbol] . "\n";
    }
    */

    /*
     * Helper functions invoked by semantic actions
     */

    /**
     * Moves statements of semicolon-style namespaces into $ns->stmts and checks various error conditions.
     *
     * @param Node\Stmt[] $stmts
     * @return Node\Stmt[]
     */
<<<<<<< HEAD
    protected function handleNamespaces(array $stmts): array {
=======
    protected function handleNamespaces(array $stmts) : array {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $hasErrored = false;
        $style = $this->getNamespacingStyle($stmts);
        if (null === $style) {
            // not namespaced, nothing to do
            return $stmts;
<<<<<<< HEAD
        }
        if ('brace' === $style) {
=======
        } elseif ('brace' === $style) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            // For braced namespaces we only have to check that there are no invalid statements between the namespaces
            $afterFirstNamespace = false;
            foreach ($stmts as $stmt) {
                if ($stmt instanceof Node\Stmt\Namespace_) {
                    $afterFirstNamespace = true;
                } elseif (!$stmt instanceof Node\Stmt\HaltCompiler
                        && !$stmt instanceof Node\Stmt\Nop
                        && $afterFirstNamespace && !$hasErrored) {
                    $this->emitError(new Error(
                        'No code may exist outside of namespace {}', $stmt->getAttributes()));
                    $hasErrored = true; // Avoid one error for every statement
                }
            }
            return $stmts;
        } else {
            // For semicolon namespaces we have to move the statements after a namespace declaration into ->stmts
            $resultStmts = [];
<<<<<<< HEAD
            $targetStmts = &$resultStmts;
=======
            $targetStmts =& $resultStmts;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            $lastNs = null;
            foreach ($stmts as $stmt) {
                if ($stmt instanceof Node\Stmt\Namespace_) {
                    if ($lastNs !== null) {
                        $this->fixupNamespaceAttributes($lastNs);
                    }
                    if ($stmt->stmts === null) {
                        $stmt->stmts = [];
<<<<<<< HEAD
                        $targetStmts = &$stmt->stmts;
=======
                        $targetStmts =& $stmt->stmts;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                        $resultStmts[] = $stmt;
                    } else {
                        // This handles the invalid case of mixed style namespaces
                        $resultStmts[] = $stmt;
<<<<<<< HEAD
                        $targetStmts = &$resultStmts;
=======
                        $targetStmts =& $resultStmts;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                    }
                    $lastNs = $stmt;
                } elseif ($stmt instanceof Node\Stmt\HaltCompiler) {
                    // __halt_compiler() is not moved into the namespace
                    $resultStmts[] = $stmt;
                } else {
                    $targetStmts[] = $stmt;
                }
            }
            if ($lastNs !== null) {
                $this->fixupNamespaceAttributes($lastNs);
            }
            return $resultStmts;
        }
    }

<<<<<<< HEAD
    private function fixupNamespaceAttributes(Node\Stmt\Namespace_ $stmt): void {
=======
    private function fixupNamespaceAttributes(Node\Stmt\Namespace_ $stmt) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        // We moved the statements into the namespace node, as such the end of the namespace node
        // needs to be extended to the end of the statements.
        if (empty($stmt->stmts)) {
            return;
        }

        // We only move the builtin end attributes here. This is the best we can do with the
        // knowledge we have.
        $endAttributes = ['endLine', 'endFilePos', 'endTokenPos'];
        $lastStmt = $stmt->stmts[count($stmt->stmts) - 1];
        foreach ($endAttributes as $endAttribute) {
            if ($lastStmt->hasAttribute($endAttribute)) {
                $stmt->setAttribute($endAttribute, $lastStmt->getAttribute($endAttribute));
            }
        }
    }

<<<<<<< HEAD
    /** @return array<string, mixed> */
    private function getNamespaceErrorAttributes(Namespace_ $node): array {
        $attrs = $node->getAttributes();
        // Adjust end attributes to only cover the "namespace" keyword, not the whole namespace.
        if (isset($attrs['startLine'])) {
            $attrs['endLine'] = $attrs['startLine'];
        }
        if (isset($attrs['startTokenPos'])) {
            $attrs['endTokenPos'] = $attrs['startTokenPos'];
        }
        if (isset($attrs['startFilePos'])) {
            $attrs['endFilePos'] = $attrs['startFilePos'] + \strlen('namespace') - 1;
        }
        return $attrs;
    }

=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    /**
     * Determine namespacing style (semicolon or brace)
     *
     * @param Node[] $stmts Top-level statements.
     *
     * @return null|string One of "semicolon", "brace" or null (no namespaces)
     */
<<<<<<< HEAD
    private function getNamespacingStyle(array $stmts): ?string {
=======
    private function getNamespacingStyle(array $stmts) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $style = null;
        $hasNotAllowedStmts = false;
        foreach ($stmts as $i => $stmt) {
            if ($stmt instanceof Node\Stmt\Namespace_) {
                $currentStyle = null === $stmt->stmts ? 'semicolon' : 'brace';
                if (null === $style) {
                    $style = $currentStyle;
                    if ($hasNotAllowedStmts) {
                        $this->emitError(new Error(
                            'Namespace declaration statement has to be the very first statement in the script',
<<<<<<< HEAD
                            $this->getNamespaceErrorAttributes($stmt)
=======
                            $stmt->getLine() // Avoid marking the entire namespace as an error
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                        ));
                    }
                } elseif ($style !== $currentStyle) {
                    $this->emitError(new Error(
                        'Cannot mix bracketed namespace declarations with unbracketed namespace declarations',
<<<<<<< HEAD
                        $this->getNamespaceErrorAttributes($stmt)
=======
                        $stmt->getLine() // Avoid marking the entire namespace as an error
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                    ));
                    // Treat like semicolon style for namespace normalization
                    return 'semicolon';
                }
                continue;
            }

            /* declare(), __halt_compiler() and nops can be used before a namespace declaration */
            if ($stmt instanceof Node\Stmt\Declare_
                || $stmt instanceof Node\Stmt\HaltCompiler
                || $stmt instanceof Node\Stmt\Nop) {
                continue;
            }

            /* There may be a hashbang line at the very start of the file */
            if ($i === 0 && $stmt instanceof Node\Stmt\InlineHTML && preg_match('/\A#!.*\r?\n\z/', $stmt->value)) {
                continue;
            }

            /* Everything else if forbidden before namespace declarations */
            $hasNotAllowedStmts = true;
        }
        return $style;
    }

<<<<<<< HEAD
    /** @return Name|Identifier */
    protected function handleBuiltinTypes(Name $name) {
=======
    /**
     * Fix up parsing of static property calls in PHP 5.
     *
     * In PHP 5 A::$b[c][d] and A::$b[c][d]() have very different interpretation. The former is
     * interpreted as (A::$b)[c][d], while the latter is the same as A::{$b[c][d]}(). We parse the
     * latter as the former initially and this method fixes the AST into the correct form when we
     * encounter the "()".
     *
     * @param  Node\Expr\StaticPropertyFetch|Node\Expr\ArrayDimFetch $prop
     * @param  Node\Arg[] $args
     * @param  array      $attributes
     *
     * @return Expr\StaticCall
     */
    protected function fixupPhp5StaticPropCall($prop, array $args, array $attributes) : Expr\StaticCall {
        if ($prop instanceof Node\Expr\StaticPropertyFetch) {
            $name = $prop->name instanceof VarLikeIdentifier
                ? $prop->name->toString() : $prop->name;
            $var = new Expr\Variable($name, $prop->name->getAttributes());
            return new Expr\StaticCall($prop->class, $var, $args, $attributes);
        } elseif ($prop instanceof Node\Expr\ArrayDimFetch) {
            $tmp = $prop;
            while ($tmp->var instanceof Node\Expr\ArrayDimFetch) {
                $tmp = $tmp->var;
            }

            /** @var Expr\StaticPropertyFetch $staticProp */
            $staticProp = $tmp->var;

            // Set start attributes to attributes of innermost node
            $tmp = $prop;
            $this->fixupStartAttributes($tmp, $staticProp->name);
            while ($tmp->var instanceof Node\Expr\ArrayDimFetch) {
                $tmp = $tmp->var;
                $this->fixupStartAttributes($tmp, $staticProp->name);
            }

            $name = $staticProp->name instanceof VarLikeIdentifier
                ? $staticProp->name->toString() : $staticProp->name;
            $tmp->var = new Expr\Variable($name, $staticProp->name->getAttributes());
            return new Expr\StaticCall($staticProp->class, $prop, $args, $attributes);
        } else {
            throw new \Exception;
        }
    }

    protected function fixupStartAttributes(Node $to, Node $from) {
        $startAttributes = ['startLine', 'startFilePos', 'startTokenPos'];
        foreach ($startAttributes as $startAttribute) {
            if ($from->hasAttribute($startAttribute)) {
                $to->setAttribute($startAttribute, $from->getAttribute($startAttribute));
            }
        }
    }

    protected function handleBuiltinTypes(Name $name) {
        $builtinTypes = [
            'bool'     => true,
            'int'      => true,
            'float'    => true,
            'string'   => true,
            'iterable' => true,
            'void'     => true,
            'object'   => true,
            'null'     => true,
            'false'    => true,
            'mixed'    => true,
            'never'    => true,
            'true'     => true,
        ];

>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        if (!$name->isUnqualified()) {
            return $name;
        }

        $lowerName = $name->toLowerString();
<<<<<<< HEAD
        if (!$this->phpVersion->supportsBuiltinType($lowerName)) {
=======
        if (!isset($builtinTypes[$lowerName])) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            return $name;
        }

        return new Node\Identifier($lowerName, $name->getAttributes());
    }

    /**
     * Get combined start and end attributes at a stack location
     *
<<<<<<< HEAD
     * @param int $stackPos Stack location
     *
     * @return array<string, mixed> Combined start and end attributes
     */
    protected function getAttributesAt(int $stackPos): array {
        return $this->getAttributes($this->tokenStartStack[$stackPos], $this->tokenEndStack[$stackPos]);
    }

    protected function getFloatCastKind(string $cast): int {
=======
     * @param int $pos Stack location
     *
     * @return array Combined start and end attributes
     */
    protected function getAttributesAt(int $pos) : array {
        return $this->startAttributeStack[$pos] + $this->endAttributeStack[$pos];
    }

    protected function getFloatCastKind(string $cast): int
    {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $cast = strtolower($cast);
        if (strpos($cast, 'float') !== false) {
            return Double::KIND_FLOAT;
        }

        if (strpos($cast, 'real') !== false) {
            return Double::KIND_REAL;
        }

        return Double::KIND_DOUBLE;
    }

<<<<<<< HEAD
    /** @param array<string, mixed> $attributes */
    protected function parseLNumber(string $str, array $attributes, bool $allowInvalidOctal = false): Int_ {
        try {
            return Int_::fromString($str, $attributes, $allowInvalidOctal);
        } catch (Error $error) {
            $this->emitError($error);
            // Use dummy value
            return new Int_(0, $attributes);
=======
    protected function parseLNumber($str, $attributes, $allowInvalidOctal = false) {
        try {
            return LNumber::fromString($str, $attributes, $allowInvalidOctal);
        } catch (Error $error) {
            $this->emitError($error);
            // Use dummy value
            return new LNumber(0, $attributes);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        }
    }

    /**
     * Parse a T_NUM_STRING token into either an integer or string node.
     *
<<<<<<< HEAD
     * @param string $str Number string
     * @param array<string, mixed> $attributes Attributes
     *
     * @return Int_|String_ Integer or string node.
=======
     * @param string $str        Number string
     * @param array  $attributes Attributes
     *
     * @return LNumber|String_ Integer or string node.
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    protected function parseNumString(string $str, array $attributes) {
        if (!preg_match('/^(?:0|-?[1-9][0-9]*)$/', $str)) {
            return new String_($str, $attributes);
        }

        $num = +$str;
        if (!is_int($num)) {
            return new String_($str, $attributes);
        }

<<<<<<< HEAD
        return new Int_($num, $attributes);
    }

    /** @param array<string, mixed> $attributes */
    protected function stripIndentation(
        string $string, int $indentLen, string $indentChar,
        bool $newlineAtStart, bool $newlineAtEnd, array $attributes
    ): string {
=======
        return new LNumber($num, $attributes);
    }

    protected function stripIndentation(
        string $string, int $indentLen, string $indentChar,
        bool $newlineAtStart, bool $newlineAtEnd, array $attributes
    ) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        if ($indentLen === 0) {
            return $string;
        }

        $start = $newlineAtStart ? '(?:(?<=\n)|\A)' : '(?<=\n)';
        $end = $newlineAtEnd ? '(?:(?=[\r\n])|\z)' : '(?=[\r\n])';
        $regex = '/' . $start . '([ \t]*)(' . $end . ')?/';
        return preg_replace_callback(
            $regex,
            function ($matches) use ($indentLen, $indentChar, $attributes) {
                $prefix = substr($matches[1], 0, $indentLen);
                if (false !== strpos($prefix, $indentChar === " " ? "\t" : " ")) {
                    $this->emitError(new Error(
                        'Invalid indentation - tabs and spaces cannot be mixed', $attributes
                    ));
                } elseif (strlen($prefix) < $indentLen && !isset($matches[2])) {
                    $this->emitError(new Error(
                        'Invalid body indentation level ' .
                        '(expecting an indentation level of at least ' . $indentLen . ')',
                        $attributes
                    ));
                }
                return substr($matches[0], strlen($prefix));
            },
            $string
        );
    }

<<<<<<< HEAD
    /**
     * @param string|(Expr|InterpolatedStringPart)[] $contents
     * @param array<string, mixed> $attributes
     * @param array<string, mixed> $endTokenAttributes
     */
    protected function parseDocString(
        string $startToken, $contents, string $endToken,
        array $attributes, array $endTokenAttributes, bool $parseUnicodeEscape
    ): Expr {
=======
    protected function parseDocString(
        string $startToken, $contents, string $endToken,
        array $attributes, array $endTokenAttributes, bool $parseUnicodeEscape
    ) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $kind = strpos($startToken, "'") === false
            ? String_::KIND_HEREDOC : String_::KIND_NOWDOC;

        $regex = '/\A[bB]?<<<[ \t]*[\'"]?([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)[\'"]?(?:\r\n|\n|\r)\z/';
        $result = preg_match($regex, $startToken, $matches);
        assert($result === 1);
        $label = $matches[1];

        $result = preg_match('/\A[ \t]*/', $endToken, $matches);
        assert($result === 1);
        $indentation = $matches[0];

        $attributes['kind'] = $kind;
        $attributes['docLabel'] = $label;
        $attributes['docIndentation'] = $indentation;

        $indentHasSpaces = false !== strpos($indentation, " ");
        $indentHasTabs = false !== strpos($indentation, "\t");
        if ($indentHasSpaces && $indentHasTabs) {
            $this->emitError(new Error(
                'Invalid indentation - tabs and spaces cannot be mixed',
                $endTokenAttributes
            ));

            // Proceed processing as if this doc string is not indented
            $indentation = '';
        }

        $indentLen = \strlen($indentation);
        $indentChar = $indentHasSpaces ? " " : "\t";

        if (\is_string($contents)) {
            if ($contents === '') {
<<<<<<< HEAD
                $attributes['rawValue'] = $contents;
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                return new String_('', $attributes);
            }

            $contents = $this->stripIndentation(
                $contents, $indentLen, $indentChar, true, true, $attributes
            );
            $contents = preg_replace('~(\r\n|\n|\r)\z~', '', $contents);
<<<<<<< HEAD
            $attributes['rawValue'] = $contents;
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

            if ($kind === String_::KIND_HEREDOC) {
                $contents = String_::parseEscapeSequences($contents, null, $parseUnicodeEscape);
            }

            return new String_($contents, $attributes);
        } else {
            assert(count($contents) > 0);
<<<<<<< HEAD
            if (!$contents[0] instanceof Node\InterpolatedStringPart) {
=======
            if (!$contents[0] instanceof Node\Scalar\EncapsedStringPart) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                // If there is no leading encapsed string part, pretend there is an empty one
                $this->stripIndentation(
                    '', $indentLen, $indentChar, true, false, $contents[0]->getAttributes()
                );
            }

            $newContents = [];
            foreach ($contents as $i => $part) {
<<<<<<< HEAD
                if ($part instanceof Node\InterpolatedStringPart) {
=======
                if ($part instanceof Node\Scalar\EncapsedStringPart) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                    $isLast = $i === \count($contents) - 1;
                    $part->value = $this->stripIndentation(
                        $part->value, $indentLen, $indentChar,
                        $i === 0, $isLast, $part->getAttributes()
                    );
<<<<<<< HEAD
                    if ($isLast) {
                        $part->value = preg_replace('~(\r\n|\n|\r)\z~', '', $part->value);
                    }
                    $part->setAttribute('rawValue', $part->value);
                    $part->value = String_::parseEscapeSequences($part->value, null, $parseUnicodeEscape);
=======
                    $part->value = String_::parseEscapeSequences($part->value, null, $parseUnicodeEscape);
                    if ($isLast) {
                        $part->value = preg_replace('~(\r\n|\n|\r)\z~', '', $part->value);
                    }
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                    if ('' === $part->value) {
                        continue;
                    }
                }
                $newContents[] = $part;
            }
<<<<<<< HEAD
            return new InterpolatedString($newContents, $attributes);
        }
    }

    protected function createCommentFromToken(Token $token, int $tokenPos): Comment {
        assert($token->id === \T_COMMENT || $token->id == \T_DOC_COMMENT);
        return \T_DOC_COMMENT === $token->id
            ? new Comment\Doc($token->text, $token->line, $token->pos, $tokenPos,
                $token->getEndLine(), $token->getEndPos() - 1, $tokenPos)
            : new Comment($token->text, $token->line, $token->pos, $tokenPos,
                $token->getEndLine(), $token->getEndPos() - 1, $tokenPos);
    }

    /**
     * Get last comment before the given token position, if any
     */
    protected function getCommentBeforeToken(int $tokenPos): ?Comment {
        while (--$tokenPos >= 0) {
            $token = $this->tokens[$tokenPos];
            if (!isset($this->dropTokens[$token->id])) {
                break;
            }

            if ($token->id === \T_COMMENT || $token->id === \T_DOC_COMMENT) {
                return $this->createCommentFromToken($token, $tokenPos);
            }
        }
        return null;
    }

    /**
     * Create a zero-length nop to capture preceding comments, if any.
     */
    protected function maybeCreateZeroLengthNop(int $tokenPos): ?Nop {
        $comment = $this->getCommentBeforeToken($tokenPos);
        if ($comment === null) {
            return null;
        }

        $commentEndLine = $comment->getEndLine();
        $commentEndFilePos = $comment->getEndFilePos();
        $commentEndTokenPos = $comment->getEndTokenPos();
        $attributes = [
            'startLine' => $commentEndLine,
            'endLine' => $commentEndLine,
            'startFilePos' => $commentEndFilePos + 1,
            'endFilePos' => $commentEndFilePos,
            'startTokenPos' => $commentEndTokenPos + 1,
            'endTokenPos' => $commentEndTokenPos,
        ];
        return new Nop($attributes);
    }

    protected function maybeCreateNop(int $tokenStartPos, int $tokenEndPos): ?Nop {
        if ($this->getCommentBeforeToken($tokenStartPos) === null) {
            return null;
        }
        return new Nop($this->getAttributes($tokenStartPos, $tokenEndPos));
    }

    protected function handleHaltCompiler(): string {
        // Prevent the lexer from returning any further tokens.
        $nextToken = $this->tokens[$this->tokenPos + 1];
        $this->tokenPos = \count($this->tokens) - 2;

        // Return text after __halt_compiler.
        return $nextToken->id === \T_INLINE_HTML ? $nextToken->text : '';
    }

    protected function inlineHtmlHasLeadingNewline(int $stackPos): bool {
        $tokenPos = $this->tokenStartStack[$stackPos];
        $token = $this->tokens[$tokenPos];
        assert($token->id == \T_INLINE_HTML);
        if ($tokenPos > 0) {
            $prevToken = $this->tokens[$tokenPos - 1];
            assert($prevToken->id == \T_CLOSE_TAG);
            return false !== strpos($prevToken->text, "\n")
                || false !== strpos($prevToken->text, "\r");
        }
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    protected function createEmptyElemAttributes(int $tokenPos): array {
        return $this->getAttributesForToken($tokenPos);
    }

    protected function fixupArrayDestructuring(Array_ $node): Expr\List_ {
        $this->createdArrays->detach($node);
        return new Expr\List_(array_map(function (Node\ArrayItem $item) {
            if ($item->value instanceof Expr\Error) {
                // We used Error as a placeholder for empty elements, which are legal for destructuring.
                return null;
            }
            if ($item->value instanceof Array_) {
                return new Node\ArrayItem(
                    $this->fixupArrayDestructuring($item->value),
                    $item->key, $item->byRef, $item->getAttributes());
            }
            return $item;
        }, $node->items), ['kind' => Expr\List_::KIND_ARRAY] + $node->getAttributes());
    }

    protected function postprocessList(Expr\List_ $node): void {
        foreach ($node->items as $i => $item) {
            if ($item->value instanceof Expr\Error) {
                // We used Error as a placeholder for empty elements, which are legal for destructuring.
                $node->items[$i] = null;
            }
        }
    }

    /** @param ElseIf_|Else_ $node */
    protected function fixupAlternativeElse($node): void {
=======
            return new Encapsed($newContents, $attributes);
        }
    }

    /**
     * Create attributes for a zero-length common-capturing nop.
     *
     * @param Comment[] $comments
     * @return array
     */
    protected function createCommentNopAttributes(array $comments) {
        $comment = $comments[count($comments) - 1];
        $commentEndLine = $comment->getEndLine();
        $commentEndFilePos = $comment->getEndFilePos();
        $commentEndTokenPos = $comment->getEndTokenPos();

        $attributes = ['comments' => $comments];
        if (-1 !== $commentEndLine) {
            $attributes['startLine'] = $commentEndLine;
            $attributes['endLine'] = $commentEndLine;
        }
        if (-1 !== $commentEndFilePos) {
            $attributes['startFilePos'] = $commentEndFilePos + 1;
            $attributes['endFilePos'] = $commentEndFilePos;
        }
        if (-1 !== $commentEndTokenPos) {
            $attributes['startTokenPos'] = $commentEndTokenPos + 1;
            $attributes['endTokenPos'] = $commentEndTokenPos;
        }
        return $attributes;
    }

    /** @param ElseIf_|Else_ $node */
    protected function fixupAlternativeElse($node) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        // Make sure a trailing nop statement carrying comments is part of the node.
        $numStmts = \count($node->stmts);
        if ($numStmts !== 0 && $node->stmts[$numStmts - 1] instanceof Nop) {
            $nopAttrs = $node->stmts[$numStmts - 1]->getAttributes();
            if (isset($nopAttrs['endLine'])) {
                $node->setAttribute('endLine', $nopAttrs['endLine']);
            }
            if (isset($nopAttrs['endFilePos'])) {
                $node->setAttribute('endFilePos', $nopAttrs['endFilePos']);
            }
            if (isset($nopAttrs['endTokenPos'])) {
                $node->setAttribute('endTokenPos', $nopAttrs['endTokenPos']);
            }
        }
    }

<<<<<<< HEAD
    protected function checkClassModifier(int $a, int $b, int $modifierPos): void {
        try {
            Modifiers::verifyClassModifier($a, $b);
=======
    protected function checkClassModifier($a, $b, $modifierPos) {
        try {
            Class_::verifyClassModifier($a, $b);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        } catch (Error $error) {
            $error->setAttributes($this->getAttributesAt($modifierPos));
            $this->emitError($error);
        }
    }

<<<<<<< HEAD
    protected function checkModifier(int $a, int $b, int $modifierPos): void {
        // Jumping through some hoops here because verifyModifier() is also used elsewhere
        try {
            Modifiers::verifyModifier($a, $b);
=======
    protected function checkModifier($a, $b, $modifierPos) {
        // Jumping through some hoops here because verifyModifier() is also used elsewhere
        try {
            Class_::verifyModifier($a, $b);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        } catch (Error $error) {
            $error->setAttributes($this->getAttributesAt($modifierPos));
            $this->emitError($error);
        }
    }

<<<<<<< HEAD
    protected function checkParam(Param $node): void {
=======
    protected function checkParam(Param $node) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        if ($node->variadic && null !== $node->default) {
            $this->emitError(new Error(
                'Variadic parameter cannot have a default value',
                $node->default->getAttributes()
            ));
        }
    }

<<<<<<< HEAD
    protected function checkTryCatch(TryCatch $node): void {
=======
    protected function checkTryCatch(TryCatch $node) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        if (empty($node->catches) && null === $node->finally) {
            $this->emitError(new Error(
                'Cannot use try without catch or finally', $node->getAttributes()
            ));
        }
    }

<<<<<<< HEAD
    protected function checkNamespace(Namespace_ $node): void {
=======
    protected function checkNamespace(Namespace_ $node) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        if (null !== $node->stmts) {
            foreach ($node->stmts as $stmt) {
                if ($stmt instanceof Namespace_) {
                    $this->emitError(new Error(
                        'Namespace declarations cannot be nested', $stmt->getAttributes()
                    ));
                }
            }
        }
    }

<<<<<<< HEAD
    private function checkClassName(?Identifier $name, int $namePos): void {
=======
    private function checkClassName($name, $namePos) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        if (null !== $name && $name->isSpecialClassName()) {
            $this->emitError(new Error(
                sprintf('Cannot use \'%s\' as class name as it is reserved', $name),
                $this->getAttributesAt($namePos)
            ));
        }
    }

<<<<<<< HEAD
    /** @param Name[] $interfaces */
    private function checkImplementedInterfaces(array $interfaces): void {
=======
    private function checkImplementedInterfaces(array $interfaces) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        foreach ($interfaces as $interface) {
            if ($interface->isSpecialClassName()) {
                $this->emitError(new Error(
                    sprintf('Cannot use \'%s\' as interface name as it is reserved', $interface),
                    $interface->getAttributes()
                ));
            }
        }
    }

<<<<<<< HEAD
    protected function checkClass(Class_ $node, int $namePos): void {
=======
    protected function checkClass(Class_ $node, $namePos) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $this->checkClassName($node->name, $namePos);

        if ($node->extends && $node->extends->isSpecialClassName()) {
            $this->emitError(new Error(
                sprintf('Cannot use \'%s\' as class name as it is reserved', $node->extends),
                $node->extends->getAttributes()
            ));
        }

        $this->checkImplementedInterfaces($node->implements);
    }

<<<<<<< HEAD
    protected function checkInterface(Interface_ $node, int $namePos): void {
=======
    protected function checkInterface(Interface_ $node, $namePos) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $this->checkClassName($node->name, $namePos);
        $this->checkImplementedInterfaces($node->extends);
    }

<<<<<<< HEAD
    protected function checkEnum(Enum_ $node, int $namePos): void {
=======
    protected function checkEnum(Enum_ $node, $namePos) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $this->checkClassName($node->name, $namePos);
        $this->checkImplementedInterfaces($node->implements);
    }

<<<<<<< HEAD
    protected function checkClassMethod(ClassMethod $node, int $modifierPos): void {
        if ($node->flags & Modifiers::STATIC) {
=======
    protected function checkClassMethod(ClassMethod $node, $modifierPos) {
        if ($node->flags & Class_::MODIFIER_STATIC) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            switch ($node->name->toLowerString()) {
                case '__construct':
                    $this->emitError(new Error(
                        sprintf('Constructor %s() cannot be static', $node->name),
                        $this->getAttributesAt($modifierPos)));
                    break;
                case '__destruct':
                    $this->emitError(new Error(
                        sprintf('Destructor %s() cannot be static', $node->name),
                        $this->getAttributesAt($modifierPos)));
                    break;
                case '__clone':
                    $this->emitError(new Error(
                        sprintf('Clone method %s() cannot be static', $node->name),
                        $this->getAttributesAt($modifierPos)));
                    break;
            }
        }

<<<<<<< HEAD
        if ($node->flags & Modifiers::READONLY) {
=======
        if ($node->flags & Class_::MODIFIER_READONLY) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            $this->emitError(new Error(
                sprintf('Method %s() cannot be readonly', $node->name),
                $this->getAttributesAt($modifierPos)));
        }
    }

<<<<<<< HEAD
    protected function checkClassConst(ClassConst $node, int $modifierPos): void {
        if ($node->flags & Modifiers::STATIC) {
=======
    protected function checkClassConst(ClassConst $node, $modifierPos) {
        if ($node->flags & Class_::MODIFIER_STATIC) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            $this->emitError(new Error(
                "Cannot use 'static' as constant modifier",
                $this->getAttributesAt($modifierPos)));
        }
<<<<<<< HEAD
        if ($node->flags & Modifiers::ABSTRACT) {
=======
        if ($node->flags & Class_::MODIFIER_ABSTRACT) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            $this->emitError(new Error(
                "Cannot use 'abstract' as constant modifier",
                $this->getAttributesAt($modifierPos)));
        }
<<<<<<< HEAD
        if ($node->flags & Modifiers::READONLY) {
=======
        if ($node->flags & Class_::MODIFIER_READONLY) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            $this->emitError(new Error(
                "Cannot use 'readonly' as constant modifier",
                $this->getAttributesAt($modifierPos)));
        }
    }

<<<<<<< HEAD
    protected function checkProperty(Property $node, int $modifierPos): void {
        if ($node->flags & Modifiers::ABSTRACT) {
=======
    protected function checkProperty(Property $node, $modifierPos) {
        if ($node->flags & Class_::MODIFIER_ABSTRACT) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            $this->emitError(new Error('Properties cannot be declared abstract',
                $this->getAttributesAt($modifierPos)));
        }

<<<<<<< HEAD
        if ($node->flags & Modifiers::FINAL) {
=======
        if ($node->flags & Class_::MODIFIER_FINAL) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            $this->emitError(new Error('Properties cannot be declared final',
                $this->getAttributesAt($modifierPos)));
        }
    }

<<<<<<< HEAD
    protected function checkUseUse(UseItem $node, int $namePos): void {
=======
    protected function checkUseUse(UseUse $node, $namePos) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        if ($node->alias && $node->alias->isSpecialClassName()) {
            $this->emitError(new Error(
                sprintf(
                    'Cannot use %s as %s because \'%2$s\' is a special class name',
                    $node->name, $node->alias
                ),
                $this->getAttributesAt($namePos)
            ));
        }
    }
<<<<<<< HEAD

    /**
     * Creates the token map.
     *
     * The token map maps the PHP internal token identifiers
     * to the identifiers used by the Parser. Additionally it
     * maps T_OPEN_TAG_WITH_ECHO to T_ECHO and T_CLOSE_TAG to ';'.
     *
     * @return array<int, int> The token map
     */
    protected function createTokenMap(): array {
        $tokenMap = [];

        for ($i = 0; $i < 1000; ++$i) {
            if ($i < 256) {
                // Single-char tokens use an identity mapping.
                $tokenMap[$i] = $i;
            } elseif (\T_DOUBLE_COLON === $i) {
                // T_DOUBLE_COLON is equivalent to T_PAAMAYIM_NEKUDOTAYIM
                $tokenMap[$i] = static::T_PAAMAYIM_NEKUDOTAYIM;
            } elseif (\T_OPEN_TAG_WITH_ECHO === $i) {
                // T_OPEN_TAG_WITH_ECHO with dropped T_OPEN_TAG results in T_ECHO
                $tokenMap[$i] = static::T_ECHO;
            } elseif (\T_CLOSE_TAG === $i) {
                // T_CLOSE_TAG is equivalent to ';'
                $tokenMap[$i] = ord(';');
            } elseif ('UNKNOWN' !== $name = token_name($i)) {
                if (defined($name = static::class . '::' . $name)) {
                    // Other tokens can be mapped directly
                    $tokenMap[$i] = constant($name);
                }
            }
        }

        // Assign tokens for which we define compatibility constants, as token_name() does not know them.
        $tokenMap[\T_FN] = static::T_FN;
        $tokenMap[\T_COALESCE_EQUAL] = static::T_COALESCE_EQUAL;
        $tokenMap[\T_NAME_QUALIFIED] = static::T_NAME_QUALIFIED;
        $tokenMap[\T_NAME_FULLY_QUALIFIED] = static::T_NAME_FULLY_QUALIFIED;
        $tokenMap[\T_NAME_RELATIVE] = static::T_NAME_RELATIVE;
        $tokenMap[\T_MATCH] = static::T_MATCH;
        $tokenMap[\T_NULLSAFE_OBJECT_OPERATOR] = static::T_NULLSAFE_OBJECT_OPERATOR;
        $tokenMap[\T_ATTRIBUTE] = static::T_ATTRIBUTE;
        $tokenMap[\T_AMPERSAND_NOT_FOLLOWED_BY_VAR_OR_VARARG] = static::T_AMPERSAND_NOT_FOLLOWED_BY_VAR_OR_VARARG;
        $tokenMap[\T_AMPERSAND_FOLLOWED_BY_VAR_OR_VARARG] = static::T_AMPERSAND_FOLLOWED_BY_VAR_OR_VARARG;
        $tokenMap[\T_ENUM] = static::T_ENUM;
        $tokenMap[\T_READONLY] = static::T_READONLY;

        // We have create a map from PHP token IDs to external symbol IDs.
        // Now map them to the internal symbol ID.
        $fullTokenMap = [];
        foreach ($tokenMap as $phpToken => $extSymbol) {
            $intSymbol = $this->tokenToSymbol[$extSymbol];
            if ($intSymbol === $this->invalidSymbol) {
                continue;
            }
            $fullTokenMap[$phpToken] = $intSymbol;
        }

        return $fullTokenMap;
    }
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
}
