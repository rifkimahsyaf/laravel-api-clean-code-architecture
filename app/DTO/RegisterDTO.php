<?php

namespace App\DTO;

class RegisterDTO
{
    public string $email;
    public string $name;
    public string $password;

    public function __construct(
        string $email, string $name, string $password
    )
    {
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
    }

    public function toArray() {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'password' => $this->password
        ];
    }
}