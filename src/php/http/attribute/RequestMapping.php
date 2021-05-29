<?php declare(strict_types=1);


namespace src\http\attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class RequestMapping {
    private string $requestMethod;
    private string $requestUrl;

    public function __construct(string $requestMethod, string $requestURL) {
        $this->requestMethod = $requestMethod;
        $this->requestUrl = $requestURL;
    }

    public function getRequestMethod(): string {
        return $this->requestMethod;
    }

    public function getRequestUrl(): string {
        return $this->requestUrl;
    }
}
