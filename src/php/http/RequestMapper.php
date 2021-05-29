<?php declare(strict_types=1);


namespace src\http;


use ReflectionClass;
use src\controller\dashboard\Controller;
use src\controller\dashboard\rest\auth\SignUpController;
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
        try {
            foreach ($this->controllers as $controller) {
                $reflectionClass = new ReflectionClass($controller);
                $requestMappingAttribute = $reflectionClass->getAttributes(RequestMapping::class)[0];
                $arguments = $requestMappingAttribute->getArguments();
                if ($arguments[0] === RequestInfo::getRequestMethod() && $arguments[1] === RequestInfo::getRequestUri()) {
                    $constructor = $reflectionClass->getConstructor();
                    $constructorArguments = [];
                    if ($constructor) {
                        foreach ($constructor->getParameters() as $constructorParameter) {
                            foreach($constructorParameter->getAttributes(QueryParam::class) as $queryParamAttribute) {
                                $constructorArguments[] = $_GET[$queryParamAttribute->getArguments()[0]];
                            }
                        }
                    }
                    $instance = $reflectionClass->newInstanceArgs($constructorArguments);
                    $instance->handleRequest();
                }
            }
        } catch (Throwable) {

        }
    }
}
