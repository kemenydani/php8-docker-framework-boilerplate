<?php declare(strict_types=1);


namespace src\controller\dashboard\rest\auth;

use src\constant\RequestMethod;
use src\controller\dashboard\Controller;
use src\http\attribute\RequestMapping;

#[RequestMapping(RequestMethod::GET, "/dashboard/rest/auth/sign-up")]
final class SignUpController implements Controller {

    public function __construct() {
        echo 'constr';
    }

    public function handleRequest(): void {
        echo 'handle';
    }
}
