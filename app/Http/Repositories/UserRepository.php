<?php
namespace App\Http\Repositories;

use App\Http\Repositories\Interface\UserRepositoryInterface;

// Entity
use App\Http\Entity\ {
  UserEntity,
  RefreshTokenEntity
};

// Models
use App\Models\ {
  User,
  RefreshToken
};

// Exception
use App\Exceptions\CustomException;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class UserRepository implements UserRepositoryInterface
{
    public function findByEmail(UserEntity $userEntity): Builder
    {
      return User::where('email', $userEntity->getEmail());
    }

    public function insert(UserEntity $userEntity): User
    {
      return DB::transaction(function () use($userEntity) {
        $userModel = new User;
        $userModel->id = $userEntity->getId();
        $userModel->name = $userEntity->getName();
        $userModel->email = $userEntity->getEmail();
        $userModel->password = $userEntity->getPassword();
        $userModel->api_token = $userEntity->getApiToken();
        $userModel->email_verified_at = Carbon::now(); //digunakan jika tidak ada email verifikasi
        throw_if(!$userModel->save(), CustomException::class, "Error Processing Request", 500);

        return $userModel;
      });
    }

    public function findById(UserEntity $userEntity): User
    {
      return User::find($userEntity->getId());
    }

    public function setResetPassword(User $userModel, UserEntity $userEntity): User
    {
      $userModel->password = $userEntity->getPassword();
      $userModel->remember_token = $userEntity->getRememberToken();
      throw_if(!$userModel->save(), CustomException::class, "Error Processing Request", 500);
      return $userModel;
    }

    public function setChangePassword(User $userModel, UserEntity $userEntity): User
    {
      $userModel->password = $userEntity->getPassword();
      $userModel->update_password_at = $userEntity->getUpdatePasswordAt();
      throw_if(!$userModel->save(), CustomException::class, "Error Processing Request", 500);
      return $userModel;
    }

    public function findByApiToken(UserEntity $userEntity): Builder
    {
      return User::where('api_token', $userEntity->getApiToken());
    }

    public function setVerification(User $userModel): User
    {
      $userModel->is_active = 1;
      $userModel->email_verified_at = Carbon::now();
      throw_if(!$userModel->save(), CustomException::class, "Error Processing Request", 500);
      return $userModel;
    }

    public function setApiToken(User $userModel, UserEntity $userEntity): User
    {
      $userModel->api_token = $userEntity->getApiToken();
      throw_if(!$userModel->save(), CustomException::class, "Error Processing Request", 500);
      return $userModel;
    }

    public function setRefreshToken(RefreshTokenEntity $refreshTokenEntity): RefreshToken
    {
      return DB::transaction(function () use($refreshTokenEntity) {
        $refreshTokenModel = new RefreshToken;
        $refreshTokenModel->id = $refreshTokenEntity->getId();
        $refreshTokenModel->user_id = $refreshTokenEntity->getUserId();
        $refreshTokenModel->token = $refreshTokenEntity->getToken();
        $refreshTokenModel->expire_at = $refreshTokenEntity->getExpireAt();
        throw_if(!$refreshTokenModel->save(), CustomException::class, "Error Processing Request", 500);
        return $refreshTokenModel;
      });
    }

    public function findByUserIdAndToken(RefreshTokenEntity $refreshTokenEntity): Builder
    {
      return RefreshToken::where('user_id', $refreshTokenEntity->getUserId())->where('token', $refreshTokenEntity->getToken());
    }

    public function setIsRevoke(RefreshToken $refreshTokenModel): RefreshToken
    {
      $refreshTokenModel->is_revoked = 1;
      throw_if(!$refreshTokenModel->save(), CustomException::class, "Error Processing Request", 500);
      return $refreshTokenModel;
    }
}
