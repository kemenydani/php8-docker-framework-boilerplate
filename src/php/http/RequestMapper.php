<?php declare(strict_types=1);


namespace src\http;


use JetBrains\PhpStorm\Pure;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use SebastianBergmann\Type\ReflectionMapper;
use src\controller\dashboard\Controller;
use src\controller\dashboard\rest\auth\SignUpController;
use src\http\attribute\Cookie;
use src\http\attribute\PathParam;
use src\http\attribute\QueryParam;
use src\http\attribute\RequestMapping;
use Throwable;

final class RequestMapper {

    /**
     * @var Controller[]
     */
    private array $controllers = [
        SignUpController::class
    ];

    public function __construct() {
    }

    public function handleRequest(RequestInfo $requestInfo): void {
        foreach ($this->controllers as $controller) {
            $reflectionClass = new ReflectionClass($controller);
            if ($this->matches($reflectionClass)) {
                $constructor = $reflectionClass->getConstructor();
                $constructorArguments = [];
                if ($constructor) {
                    $constructorArguments = $this->getConstructorArguments($constructor);
                }
                $this->loadController($reflectionClass, $constructorArguments);
            }
        }
    }

    private function loadController(ReflectionClass $reflectionClass, array $arguments = []) {
        try {
            $instance = $reflectionClass->newInstanceArgs($arguments);
            $instance->handleRequest();
        } catch (Throwable $t) {
            throw $t;
        }
    }

    #[Pure]
    private function getConstructorArguments(ReflectionMethod $reflectionMethod): array {
        $constructorArguments = [];
        foreach ($reflectionMethod->getParameters() as $constructorParameter) {
            foreach($constructorParameter->getAttributes() as $constructorAttribute) {
                if ($constructorAttribute->getName() === QueryParam::class) {
                    $queryParam = new QueryParam(...$constructorAttribute->getArguments());
                    $constructorArguments[] = $queryParam->getValue();
                }
                if ($constructorAttribute->getName() === Cookie::class) {
                    $cookie = new Cookie(...$constructorAttribute->getArguments());
                    $constructorArguments[] = $cookie->getValue();
                }
            }
        }
        return $constructorArguments;
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
