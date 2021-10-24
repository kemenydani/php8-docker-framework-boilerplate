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
    private function getConstructorArguments(ReflectionMethod $constructor): array {
        $arguments = [];
        foreach ($constructor->getParameters() as $parameter) {
            foreach($parameter->getAttributes() as $attribute) {
                if ($attribute->getName() === QueryParam::class) {
                    $queryParam = new QueryParam(...$attribute->getArguments());
                    $arguments[] = $queryParam->getValue();
                }
                if ($attribute->getName() === RequestParam::class) {
                    $queryParam = new RequestParam(...$attribute->getArguments());
                    $arguments[] = $queryParam->getValue();
                }
                if ($attribute->getName() === Cookie::class) {
                    $cookie = new Cookie(...$attribute->getArguments());
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
