<?php declare(strict_types=1);


namespace src\controller\dashboard;


interface Controller {
    public function handleRequest(): void;
}