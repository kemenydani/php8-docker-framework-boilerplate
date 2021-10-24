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
                switch ($attribute->getName()) {
                    case QueryParam::class:
                        $attribute = new QueryParam(...$attribute->getArguments());
                        $arguments[] = $attribute->getValue();
                        break;
                    case RequestParam::class:
                        $attribute = new RequestParam(...$attribute->getArguments());
                        $arguments[] = $attribute->getValue();
                        break;
                    case Cookie::class:
                        $attribute = new Cookie(...$attribute->getArguments());
                        $arguments[] = $attribute->getValue();
                        break;
                    default:
                        $arguments[] = $parameter->getDefaultValue();
                        break;
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
