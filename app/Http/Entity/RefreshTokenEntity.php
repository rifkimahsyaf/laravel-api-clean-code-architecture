<?php
namespace App\Http\Entity;

class RefreshTokenEntity
{
    private $id;
    private $userId;
    private $token;
    private $isRevoked;
    private $expireAt;

    public function setId($id) {
        $this->id = $id;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setToken($token) {
        $this->token = $token;
    }

    public function setIsRevoked($isRevoked) {
        $this->isRevoked = $isRevoked;
    }

    public function setExpireAt($expireAt) {
        $this->expireAt = $expireAt;
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getToken() {
        return $this->token;
    }

    public function getIsRevoked() {
        $this->isRevoked;
    }

    public function getExpireAt() {
        return $this->expireAt;
    }
}