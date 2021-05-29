<?php declare(strict_types=1);


namespace src\http\attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
final class PathParam {
    public function __construct(private string $name) {
    }

    public function getName(): string {
        return $this->name;
    }
}
