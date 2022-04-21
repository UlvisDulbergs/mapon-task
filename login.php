<?php
declare(strict_types=1);
namespace App\Handler;

class Login{

    public function execute(): void
    {
        require_once __DIR__ . "/../view/login.phtml";
        
    }

}