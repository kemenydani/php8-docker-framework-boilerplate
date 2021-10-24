<?php declare(strict_types=1);


namespace src\http;


use JetBrains\PhpStorm\Pure;
use ReflectionAttribute;
use ReflectionClass;
use src\controller\dashboard\Controller;
use src\controller\dashboard\rest\auth\SignUpController;
use src\http\attribute\RequestMapping;
use src\http\injector\ControllerInitiator;

final class RequestMapper {

    /**
     * @var Controller[]
     */
    private array $controllers = [
        SignUpController::class
    ];

    public function handleRequest(): void {
        foreach ($this->controllers as $controller) {
            $reflectionClass = new ReflectionClass($controller);
            if ($this->matches($reflectionClass)) {
                $initiator = new ControllerInitiator($reflectionClass);
                $instance = $initiator->initiate();
                $instance->handleRequest();
            }
        }
    }

    #[Pure]
    private function getAttribute(ReflectionClass $reflectionClass, string $namespace): ReflectionAttribute {
        return $reflectionClass->getAttributes($namespace)[0];
    }

    #[Pure]
    private function getRequestMappingAttribute(ReflectionClass $reflectionClass): RequestMapping {
        $requestMappingAttribute = $this->getAttribute($reflectionClass, RequestMapping::class);
        return new RequestMapping(...$requestMappingAttribute->getArguments());
    }

    private function matches(ReflectionClass $reflectionClass): bool {
        $requestMappingAttribute = $this->getRequestMappingAttribute($reflectionClass);
        return $requestMappingAttribute->matchesWithRequestMethod(RequestInfo::getRequestMethod(),
            RequestInfo::getRequestPath());
    }
}
