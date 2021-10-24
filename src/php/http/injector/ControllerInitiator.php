<?php

namespace src\http\injector;

use JetBrains\PhpStorm\Pure;
use ReflectionClass;
use ReflectionMethod;
use src\controller\dashboard\Controller;
use src\http\attribute\Cookie;
use src\http\attribute\QueryParam;
use src\http\attribute\RequestParam;
use Throwable;

final class ControllerInitiator {

    private ReflectionClass $controllerReflection;

    public function __construct(ReflectionClass $controllerReflection) {
        $this->controllerReflection = $controllerReflection;
    }

    #[Pure]
    private function getConstructorArguments(ReflectionMethod $reflectionMethod): array {
        $arguments = [];
        foreach ($reflectionMethod->getParameters() as $constructorParameter) {
            foreach($constructorParameter->getAttributes() as $constructorAttribute) {
                if ($constructorAttribute->getName() === QueryParam::class) {
                    $queryParam = new QueryParam(...$constructorAttribute->getArguments());
                    $arguments[] = $queryParam->getValue();
                }
                if ($constructorAttribute->getName() === RequestParam::class) {
                    $queryParam = new RequestParam(...$constructorAttribute->getArguments());
                    $arguments[] = $queryParam->getValue();
                }
                if ($constructorAttribute->getName() === Cookie::class) {
                    $cookie = new Cookie(...$constructorAttribute->getArguments());
                    $arguments[] = $cookie->getValue();
                }
            }
        }
        return $arguments;
    }

    public function initiate(): ?Controller {
        try {
            $arguments = [];
            $constructor = $this->controllerReflection->getConstructor();
            if ($constructor) {
                $arguments = $this->getConstructorArguments($constructor);
            }

            $instance = $this->controllerReflection->newInstanceArgs($arguments);

            if ($instance instanceof Controller) {
                return $instance;
            }
        } catch (Throwable $throwable) {

        }
        return null;
    }

}
