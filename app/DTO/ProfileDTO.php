<?php

namespace App\DTO;

class ProfileDTO
{
    public string $user_id;

    public function __construct(
        string $user_id
    )
    {
        $this->user_id = $user_id;
    }

    public function toArray() {
        return [
            'user_id' => $this->user_id,
        ];
    }
}