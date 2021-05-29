<?php declare(strict_types=1);


namespace src\http;


use ReflectionClass;
use src\controller\dashboard\Controller;
use src\controller\dashboard\rest\auth\SignUpController;
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
                $attribute = $reflectionClass->getAttributes(RequestMapping::class)[0];
                $arguments = $attribute->getArguments();
                if ($arguments[0] === RequestInfo::getRequestMethod() && $arguments[1] === RequestInfo::getRequestUri()) {
                    $instance = $reflectionClass->newInstanceArgs([]);
                    $instance->handleRequest();
                }
            }
        } catch (Throwable) {

        }
    }
}
