<?php declare(strict_types=1);

require __DIR__ . "/../vendor/autoload.php";

use src\http\RequestInfo;
use src\http\RequestMapper;

(new RequestMapper())->handleRequest();
