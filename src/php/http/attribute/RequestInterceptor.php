<?php declare(strict_types=1);

namespace src\http\attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class RequestInterceptor {
    public function __construct(string $foo) {
    }
}
