<?php

namespace App\DTO;

class RefreshTokenDTO
{
    public string $email;
    public string $refresh_token;

    public function __construct(
        string $email, string $refresh_token
    )
    {
        $this->email = $email;
        $this->refresh_token = $refresh_token;
    }

    public function toArray() {
        return [
            'email' => $this->email,
            'refresh_token' => $this->refresh_token
        ];
    }
}