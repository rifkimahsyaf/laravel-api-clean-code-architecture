<?php
namespace App\Http\Entity;

class UserEntity
{
    private $id;
    private $name;
    private $email;
    private $emailVerifiedAt;
    private $password;
    private $isActive;
    private $rememberToken;
    private $updatePasswordAt;
    private $apiToken;

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setEmailVerifiedAt($emailVerifiedAt) {
        $this->emailVerifiedAt = $emailVerifiedAt;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setIsActive($isActive) {
        $this->isActive = $isActive;
    }

    public function setRememberToken($rememberToken) {
        $this->rememberToken = $rememberToken;
    }

    public function setUpdatePasswordAt($updatePasswordAt) {
        $this->updatePasswordAt = $updatePasswordAt;
    }

    public function setApiToken($apiToken) {
        $this->apiToken = $apiToken;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getEmailVerifiedAt() {
        $this->emailVerifiedAt;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getIsActive() {
        return $this->isActive;
    }

    public function getRememberToken() {
        return $this->rememberToken;
    }

    public function getUpdatePasswordAt() {
        return $this->updatePasswordAt;
    }

    public function getApiToken() {
        return $this->apiToken;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->emailVerifiedAt,
            'password' => $this->password,
            'is_active' => $this->isActive,
            'remember_token' => $this->rememberToken,
            'update_password_at' => $this->updatePasswordAt
        ];
    }
}