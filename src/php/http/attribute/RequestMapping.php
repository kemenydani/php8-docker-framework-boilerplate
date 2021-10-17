<?php declare(strict_types=1);


namespace src\http\attribute;

use Attribute;
use src\constant\RequestMethod;

#[Attribute(Attribute::TARGET_CLASS)]
final class RequestMapping {
    private RequestMethod $requestMethod;
    private string $requestUrl;

    public function __construct(RequestMethod $requestMethod, string $requestURL) {
        $this->requestMethod = $requestMethod;
        $this->requestUrl = $requestURL;
    }

    public function matchesWithRequestMethod(RequestMethod $requestMethod, string $requestUrl): bool {
        return $requestMethod->value === $this->requestMethod->value && $requestUrl === $this->requestUrl;
    }
}
