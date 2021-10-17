<?php

namespace src\http\attribute;

interface RequestAttribute {
    public function __construct(string $name);
    public function getValue(): ?string;
}
