<?php declare(strict_types=1);


namespace src\controller\dashboard\rest\auth;

use src\constant\RequestMethod;
use src\controller\dashboard\Controller;
use src\http\attribute\Cookie;
use src\http\attribute\QueryParam;
use src\http\attribute\RequestMapping;
use src\http\attribute\RequestParam;

#[RequestMapping(RequestMethod::GET, "/dashboard/rest/auth/sign-up")]
final class SignUpController implements Controller {

    public function __construct(
        #[QueryParam("foo")] ?string $queryParam,
        #[Cookie("device")] ?string $cookie,
        #[RequestParam("foo")] ?string $requestParamFoo,
        #[RequestParam("device")] ?string $requestParamDevice)
    {
        echo 'constr';
        echo $queryParam;
        echo $cookie;
        echo $requestParamDevice;
        echo $requestParamFoo;
    }

    public function handleRequest(): void {
        echo 'handle';
    }
}
