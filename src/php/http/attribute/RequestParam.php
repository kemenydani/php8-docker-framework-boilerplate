<?php

namespace src\http\attribute;

interface RequestParam {
    public function getValue(): ?string;
}
