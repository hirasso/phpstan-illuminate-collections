<?php

declare(strict_types=1);

namespace Hirasso\PHPStanIlluminateCollections;

use Illuminate\Support\Enumerable;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PHPStan\Analyser\Scope;
use PHPStan\DependencyInjection\AutowiredService;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\Accessory\AccessoryArrayListType;
use PHPStan\Type\ArrayType;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ErrorType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeCombinator;

/**
 * `->values()->all()` returns `list<TValue>` instead of `array<int, TValue>`
 */
#[AutowiredService]
class CollectionValuesAllDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass(): string
    {
        return Enumerable::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection): bool
    {
        return $methodReflection->getName() === 'all';
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope,
    ): Type|null {
        $calledOn = $methodCall->var;

        if (! $calledOn instanceof MethodCall
            || ! $calledOn->name instanceof Identifier
            || $calledOn->name->toLowerString() !== 'values'
        ) {
            return null;
        }

        $valueType = $scope->getType($calledOn)->getTemplateType(Enumerable::class, 'TValue');

        if ($valueType instanceof ErrorType) {
            return null;
        }

        return TypeCombinator::intersect(
            new ArrayType(new IntegerType(), $valueType),
            new AccessoryArrayListType(),
        );
    }
}
