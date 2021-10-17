<?php declare(strict_types=1);


namespace src\http\attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class Cookie implements RequestAttribute {
    public function __construct(private string $name) {
    }

    public function getValue(): ?string {
        return @$_COOKIE[$this->name];
    }
}
