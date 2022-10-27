<?php

namespace App\Dto;

class UserDto
{
    private string $account;
    private string $password;
    private string $name;
    private string $email;

    public function __construct(
        string $account,
        string $password,
        string $name,
        string $email,
    ) {
        $this->account = $account;
        $this->password = $password;
        $this->name = $name;
        $this->email = $email;
    }

    public function getAccount(): string
    {
        return $this->account;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getName()
    {
        return $this->name;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
}
