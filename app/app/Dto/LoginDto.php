<?php

namespace App\Dto;

use App\Dto\Dto;

class LoginDto
{
    private string $account = '';
    private string $password = '';

    public function __construct(
        string $account,
        string $password,
    ) {
        $this->account = $account;
        $this->password = $password;
    }

    public function getAccount(): string
    {
        return $this->account;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
}
