<?php declare(strict_types=1);

namespace PhpParser;

use PhpParser\Node\Arg;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Use_;

<<<<<<< HEAD
class BuilderFactory {
=======
class BuilderFactory
{
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    /**
     * Creates an attribute node.
     *
     * @param string|Name $name Name of the attribute
<<<<<<< HEAD
     * @param array $args Attribute named arguments
     */
    public function attribute($name, array $args = []): Node\Attribute {
=======
     * @param array       $args Attribute named arguments
     *
     * @return Node\Attribute
     */
    public function attribute($name, array $args = []) : Node\Attribute {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Node\Attribute(
            BuilderHelpers::normalizeName($name),
            $this->args($args)
        );
    }

    /**
     * Creates a namespace builder.
     *
     * @param null|string|Node\Name $name Name of the namespace
     *
     * @return Builder\Namespace_ The created namespace builder
     */
<<<<<<< HEAD
    public function namespace($name): Builder\Namespace_ {
=======
    public function namespace($name) : Builder\Namespace_ {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Builder\Namespace_($name);
    }

    /**
     * Creates a class builder.
     *
     * @param string $name Name of the class
     *
     * @return Builder\Class_ The created class builder
     */
<<<<<<< HEAD
    public function class(string $name): Builder\Class_ {
=======
    public function class(string $name) : Builder\Class_ {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Builder\Class_($name);
    }

    /**
     * Creates an interface builder.
     *
     * @param string $name Name of the interface
     *
     * @return Builder\Interface_ The created interface builder
     */
<<<<<<< HEAD
    public function interface(string $name): Builder\Interface_ {
=======
    public function interface(string $name) : Builder\Interface_ {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Builder\Interface_($name);
    }

    /**
     * Creates a trait builder.
     *
     * @param string $name Name of the trait
     *
     * @return Builder\Trait_ The created trait builder
     */
<<<<<<< HEAD
    public function trait(string $name): Builder\Trait_ {
=======
    public function trait(string $name) : Builder\Trait_ {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Builder\Trait_($name);
    }

    /**
     * Creates an enum builder.
     *
     * @param string $name Name of the enum
     *
     * @return Builder\Enum_ The created enum builder
     */
<<<<<<< HEAD
    public function enum(string $name): Builder\Enum_ {
=======
    public function enum(string $name) : Builder\Enum_ {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Builder\Enum_($name);
    }

    /**
     * Creates a trait use builder.
     *
     * @param Node\Name|string ...$traits Trait names
     *
<<<<<<< HEAD
     * @return Builder\TraitUse The created trait use builder
     */
    public function useTrait(...$traits): Builder\TraitUse {
=======
     * @return Builder\TraitUse The create trait use builder
     */
    public function useTrait(...$traits) : Builder\TraitUse {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Builder\TraitUse(...$traits);
    }

    /**
     * Creates a trait use adaptation builder.
     *
<<<<<<< HEAD
     * @param Node\Name|string|null $trait Trait name
     * @param Node\Identifier|string $method Method name
     *
     * @return Builder\TraitUseAdaptation The created trait use adaptation builder
     */
    public function traitUseAdaptation($trait, $method = null): Builder\TraitUseAdaptation {
=======
     * @param Node\Name|string|null  $trait  Trait name
     * @param Node\Identifier|string $method Method name
     *
     * @return Builder\TraitUseAdaptation The create trait use adaptation builder
     */
    public function traitUseAdaptation($trait, $method = null) : Builder\TraitUseAdaptation {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        if ($method === null) {
            $method = $trait;
            $trait = null;
        }

        return new Builder\TraitUseAdaptation($trait, $method);
    }

    /**
     * Creates a method builder.
     *
     * @param string $name Name of the method
     *
     * @return Builder\Method The created method builder
     */
<<<<<<< HEAD
    public function method(string $name): Builder\Method {
=======
    public function method(string $name) : Builder\Method {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Builder\Method($name);
    }

    /**
     * Creates a parameter builder.
     *
     * @param string $name Name of the parameter
     *
     * @return Builder\Param The created parameter builder
     */
<<<<<<< HEAD
    public function param(string $name): Builder\Param {
=======
    public function param(string $name) : Builder\Param {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Builder\Param($name);
    }

    /**
     * Creates a property builder.
     *
     * @param string $name Name of the property
     *
     * @return Builder\Property The created property builder
     */
<<<<<<< HEAD
    public function property(string $name): Builder\Property {
=======
    public function property(string $name) : Builder\Property {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Builder\Property($name);
    }

    /**
     * Creates a function builder.
     *
     * @param string $name Name of the function
     *
     * @return Builder\Function_ The created function builder
     */
<<<<<<< HEAD
    public function function(string $name): Builder\Function_ {
=======
    public function function(string $name) : Builder\Function_ {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Builder\Function_($name);
    }

    /**
     * Creates a namespace/class use builder.
     *
     * @param Node\Name|string $name Name of the entity (namespace or class) to alias
     *
     * @return Builder\Use_ The created use builder
     */
<<<<<<< HEAD
    public function use($name): Builder\Use_ {
=======
    public function use($name) : Builder\Use_ {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Builder\Use_($name, Use_::TYPE_NORMAL);
    }

    /**
     * Creates a function use builder.
     *
     * @param Node\Name|string $name Name of the function to alias
     *
     * @return Builder\Use_ The created use function builder
     */
<<<<<<< HEAD
    public function useFunction($name): Builder\Use_ {
=======
    public function useFunction($name) : Builder\Use_ {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Builder\Use_($name, Use_::TYPE_FUNCTION);
    }

    /**
     * Creates a constant use builder.
     *
     * @param Node\Name|string $name Name of the const to alias
     *
     * @return Builder\Use_ The created use const builder
     */
<<<<<<< HEAD
    public function useConst($name): Builder\Use_ {
=======
    public function useConst($name) : Builder\Use_ {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Builder\Use_($name, Use_::TYPE_CONSTANT);
    }

    /**
     * Creates a class constant builder.
     *
<<<<<<< HEAD
     * @param string|Identifier $name Name
=======
     * @param string|Identifier                          $name  Name
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     * @param Node\Expr|bool|null|int|float|string|array $value Value
     *
     * @return Builder\ClassConst The created use const builder
     */
<<<<<<< HEAD
    public function classConst($name, $value): Builder\ClassConst {
=======
    public function classConst($name, $value) : Builder\ClassConst {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Builder\ClassConst($name, $value);
    }

    /**
     * Creates an enum case builder.
     *
<<<<<<< HEAD
     * @param string|Identifier $name Name
     *
     * @return Builder\EnumCase The created use const builder
     */
    public function enumCase($name): Builder\EnumCase {
=======
     * @param string|Identifier $name  Name
     *
     * @return Builder\EnumCase The created use const builder
     */
    public function enumCase($name) : Builder\EnumCase {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Builder\EnumCase($name);
    }

    /**
     * Creates node a for a literal value.
     *
     * @param Expr|bool|null|int|float|string|array $value $value
<<<<<<< HEAD
     */
    public function val($value): Expr {
=======
     *
     * @return Expr
     */
    public function val($value) : Expr {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return BuilderHelpers::normalizeValue($value);
    }

    /**
     * Creates variable node.
     *
     * @param string|Expr $name Name
<<<<<<< HEAD
     */
    public function var($name): Expr\Variable {
=======
     *
     * @return Expr\Variable
     */
    public function var($name) : Expr\Variable {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        if (!\is_string($name) && !$name instanceof Expr) {
            throw new \LogicException('Variable name must be string or Expr');
        }

        return new Expr\Variable($name);
    }

    /**
     * Normalizes an argument list.
     *
     * Creates Arg nodes for all arguments and converts literal values to expressions.
     *
     * @param array $args List of arguments to normalize
     *
<<<<<<< HEAD
     * @return list<Arg>
     */
    public function args(array $args): array {
=======
     * @return Arg[]
     */
    public function args(array $args) : array {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $normalizedArgs = [];
        foreach ($args as $key => $arg) {
            if (!($arg instanceof Arg)) {
                $arg = new Arg(BuilderHelpers::normalizeValue($arg));
            }
            if (\is_string($key)) {
                $arg->name = BuilderHelpers::normalizeIdentifier($key);
            }
            $normalizedArgs[] = $arg;
        }
        return $normalizedArgs;
    }

    /**
     * Creates a function call node.
     *
     * @param string|Name|Expr $name Function name
<<<<<<< HEAD
     * @param array $args Function arguments
     */
    public function funcCall($name, array $args = []): Expr\FuncCall {
=======
     * @param array            $args Function arguments
     *
     * @return Expr\FuncCall
     */
    public function funcCall($name, array $args = []) : Expr\FuncCall {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Expr\FuncCall(
            BuilderHelpers::normalizeNameOrExpr($name),
            $this->args($args)
        );
    }

    /**
     * Creates a method call node.
     *
<<<<<<< HEAD
     * @param Expr $var Variable the method is called on
     * @param string|Identifier|Expr $name Method name
     * @param array $args Method arguments
     */
    public function methodCall(Expr $var, $name, array $args = []): Expr\MethodCall {
=======
     * @param Expr                   $var  Variable the method is called on
     * @param string|Identifier|Expr $name Method name
     * @param array                  $args Method arguments
     *
     * @return Expr\MethodCall
     */
    public function methodCall(Expr $var, $name, array $args = []) : Expr\MethodCall {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Expr\MethodCall(
            $var,
            BuilderHelpers::normalizeIdentifierOrExpr($name),
            $this->args($args)
        );
    }

    /**
     * Creates a static method call node.
     *
<<<<<<< HEAD
     * @param string|Name|Expr $class Class name
     * @param string|Identifier|Expr $name Method name
     * @param array $args Method arguments
     */
    public function staticCall($class, $name, array $args = []): Expr\StaticCall {
=======
     * @param string|Name|Expr       $class Class name
     * @param string|Identifier|Expr $name  Method name
     * @param array                  $args  Method arguments
     *
     * @return Expr\StaticCall
     */
    public function staticCall($class, $name, array $args = []) : Expr\StaticCall {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Expr\StaticCall(
            BuilderHelpers::normalizeNameOrExpr($class),
            BuilderHelpers::normalizeIdentifierOrExpr($name),
            $this->args($args)
        );
    }

    /**
     * Creates an object creation node.
     *
     * @param string|Name|Expr $class Class name
<<<<<<< HEAD
     * @param array $args Constructor arguments
     */
    public function new($class, array $args = []): Expr\New_ {
=======
     * @param array            $args  Constructor arguments
     *
     * @return Expr\New_
     */
    public function new($class, array $args = []) : Expr\New_ {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Expr\New_(
            BuilderHelpers::normalizeNameOrExpr($class),
            $this->args($args)
        );
    }

    /**
     * Creates a constant fetch node.
     *
     * @param string|Name $name Constant name
<<<<<<< HEAD
     */
    public function constFetch($name): Expr\ConstFetch {
=======
     *
     * @return Expr\ConstFetch
     */
    public function constFetch($name) : Expr\ConstFetch {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Expr\ConstFetch(BuilderHelpers::normalizeName($name));
    }

    /**
     * Creates a property fetch node.
     *
<<<<<<< HEAD
     * @param Expr $var Variable holding object
     * @param string|Identifier|Expr $name Property name
     */
    public function propertyFetch(Expr $var, $name): Expr\PropertyFetch {
=======
     * @param Expr                   $var  Variable holding object
     * @param string|Identifier|Expr $name Property name
     *
     * @return Expr\PropertyFetch
     */
    public function propertyFetch(Expr $var, $name) : Expr\PropertyFetch {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        return new Expr\PropertyFetch($var, BuilderHelpers::normalizeIdentifierOrExpr($name));
    }

    /**
     * Creates a class constant fetch node.
     *
     * @param string|Name|Expr $class Class name
<<<<<<< HEAD
     * @param string|Identifier|Expr $name Constant name
=======
     * @param string|Identifier|Expr $name  Constant name
     *
     * @return Expr\ClassConstFetch
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
     */
    public function classConstFetch($class, $name): Expr\ClassConstFetch {
        return new Expr\ClassConstFetch(
            BuilderHelpers::normalizeNameOrExpr($class),
            BuilderHelpers::normalizeIdentifierOrExpr($name)
        );
    }

    /**
     * Creates nested Concat nodes from a list of expressions.
     *
     * @param Expr|string ...$exprs Expressions or literal strings
<<<<<<< HEAD
     */
    public function concat(...$exprs): Concat {
=======
     *
     * @return Concat
     */
    public function concat(...$exprs) : Concat {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        $numExprs = count($exprs);
        if ($numExprs < 2) {
            throw new \LogicException('Expected at least two expressions');
        }

        $lastConcat = $this->normalizeStringExpr($exprs[0]);
        for ($i = 1; $i < $numExprs; $i++) {
            $lastConcat = new Concat($lastConcat, $this->normalizeStringExpr($exprs[$i]));
        }
        return $lastConcat;
    }

    /**
     * @param string|Expr $expr
<<<<<<< HEAD
     */
    private function normalizeStringExpr($expr): Expr {
=======
     * @return Expr
     */
    private function normalizeStringExpr($expr) : Expr {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
        if ($expr instanceof Expr) {
            return $expr;
        }

        if (\is_string($expr)) {
            return new String_($expr);
        }

        throw new \LogicException('Expected string or Expr');
    }
}
