<?php declare(strict_types=1);


namespace src\http;

use src\constant\RequestMethod;

final class RequestInfo {

    public static function getRequestUri(): string {
        return $_SERVER["REQUEST_URI"];
    }

    public static function getRequestPath(): string {
        return preg_replace('/\?.*/', '', $_SERVER["REQUEST_URI"]);
    }

    public static function getRequestMethod(): RequestMethod {
        return RequestMethod::from($_SERVER["REQUEST_METHOD"]);
    }
}
