<?php declare(strict_types=1);


namespace src\http\attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class Cookie implements RequestParam {
    public function __construct(private string $name) {
    }

    public function getValue(): ?string {
        return @$_COOKIE[$this->name];
    }
}
