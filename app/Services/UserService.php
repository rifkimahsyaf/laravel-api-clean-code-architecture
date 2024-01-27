<?php
namespace App\Services;

// Repository
use App\Http\Repositories\Interface\ {
    UserRepositoryInterface
};

// Entity
use App\Http\Entity\ {
    UserEntity,
    RefreshTokenEntity
};

// Exception
use App\Exceptions\CustomException;

use App\Http\Helper\ {
    StringHelper
};
USE Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

use App\DTO\ {
    LoginDTO,
    RegisterDTO,
    ProfileDTO,
    RefreshTokenDTO
};

use App\Models\User;

use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class UserService
{
    private $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterDTO $registerDTO): string
    {
        $userEntity = new UserEntity();
        $userEntity->setEmail($registerDTO->email);
        $userModel = $this->userRepository->findByEmail($userEntity);

        if($userModel->exists())
            throw new CustomException("Email already registered", 409);

        $userEntity->setId((string) Str::uuid());
        $userEntity->setName($registerDTO->name);
        $userEntity->setPassword(Hash::make($registerDTO->password));
        $userEntity->setApiToken(Str::random(64));
        $userModel = $this->userRepository->insert($userEntity);

        //email verifikasi start
        // $sendMailJobDTO = new SendMailJobDTO(
        //     $template = 'email.verificationEmail',
        //     $data = [
        //         'api_token' => $userEntity->getApiToken()
        //     ],
        //     $email = $registerDTO->email,
        //     $subject = 'verification Email'
        // );
        // SendMailJob::dispatch($sendMailJobDTO);
        //email verifikasi end

        return "Account registration has been successful, please log in";
    }

    public function login(LoginDTO $loginDTO): array
    {
        $userEntity = new UserEntity();
        $userEntity->setEmail($loginDTO->email);
        $userModel = $this->userRepository->findByEmail($userEntity);

        if(!$userModel->exists())
            throw new CustomException("Email not registered.", 404);

        $credentials = $loginDTO->toArray();

        $token = auth('api')->claims(['exp' => Carbon::now()->addMinutes(env('JWT_TTL', 60))->timestamp])->attempt($credentials, ['exp' => Carbon::now()->addSecond(10)->timestamp]);
        if (!$token)
            throw new CustomException("Email or password does not match.", 401);

        $userModel = $userModel->first();

        if (!$userModel->email_verified_at)
            throw new CustomException("Your account has not been verified.", 401);

        if (!$userModel->is_active)
            throw new CustomException("Your account is deactivated.", 401);

        $refreshTokenEntity = new RefreshTokenEntity();
        $refreshTokenEntity->setId((string) Str::uuid());
        $refreshTokenEntity->setUserId($userModel->id);
        $refreshTokenEntity->setToken(StringHelper::randomString(255));
        $refreshTokenEntity->setExpireAt(Carbon::now()->addDays(7));
        $this->userRepository->setRefreshToken($refreshTokenEntity);

        return [$token, $refreshTokenEntity->getToken()];
    }

    public function refreshToken(RefreshTokenDTO $refreshTokenDTO): array
    {
        $refreshTokenTransaction = DB::transaction(function () use($refreshTokenDTO) {
            $userEntity = new UserEntity();
            $userEntity->setEmail($refreshTokenDTO->email);
            $userModel = $this->userRepository->findByEmail($userEntity);

            $userModel = $userModel->first();

            $refreshTokenEntity = new RefreshTokenEntity();
            $refreshTokenEntity->setUserId($userModel->id);
            $refreshTokenEntity->setToken($refreshTokenDTO->refresh_token);
            $refreshTokenModel = $this->userRepository->findByUserIdAndToken($refreshTokenEntity);

            if(!$refreshTokenModel->exists())
                throw new CustomException("Unauthorized", 403);

            $refreshTokenModel = $refreshTokenModel->first();

            if($refreshTokenModel->is_revoked == 1)
                throw new CustomException("Refresh Token Expired.", 422);

            try {
                $token = JWTAuth::claims(['exp' => Carbon::now()->addMinutes(env('JWT_TTL', 60))->timestamp])->refresh(JWTAuth::getToken());
            } catch (\Exception $e) {
                throw new CustomException("Token is invalid", 401);
            }
            
            $this->userRepository->setIsRevoke($refreshTokenModel);

            $refreshTokenEntity = new RefreshTokenEntity();
            $refreshTokenEntity->setId((string) Str::uuid());
            $refreshTokenEntity->setUserId($userModel->id);
            $refreshTokenEntity->setToken(StringHelper::randomString(255));
            $refreshTokenEntity->setExpireAt(Carbon::now()->addDays(7));
            $this->userRepository->setRefreshToken($refreshTokenEntity);

            return [$token, $refreshTokenEntity->getToken()];
        });
        return $refreshTokenTransaction;
    }

    public function profile(ProfileDTO $profileDTO): User
    {
        $userEntity = new UserEntity();
        $userEntity->setId($profileDTO->user_id);
        $userModel = $this->userRepository->findById($userEntity);
        return $userModel;
    }

    public function logout(): string
    {
        auth('api')->logout(true);
        return "Successfully logged out";
    }
}
?>