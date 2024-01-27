<?php
namespace App\Http\Repositories\Interface;

use App\Http\Entity\UserEntity;
use App\Http\Entity\RefreshTokenEntity;
use App\Models\User;
use App\Models\RefreshToken;

interface UserRepositoryInterface
{
    public function findByEmail(UserEntity $userEntity);
    public function insert(UserEntity $userEntity);
    public function findById(UserEntity $userEntity);
    public function setResetPassword(User $userModel, UserEntity $userEntity);
    public function setChangePassword(User $userModel, UserEntity $userEntity);
    public function findByApiToken(UserEntity $userEntity);
    public function setVerification(User $userModel);
    public function setApiToken(User $userModel, UserEntity $userEntity);
    public function setRefreshToken(RefreshTokenEntity $refreshTokenEntity);
    public function findByUserIdAndToken(RefreshTokenEntity $refreshTokenEntity);
    public function setIsRevoke(RefreshToken $refreshTokenModel);
}