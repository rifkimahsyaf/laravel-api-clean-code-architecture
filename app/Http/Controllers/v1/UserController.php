<?php
namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;

// Services
use App\Services\ {
    UserService
};

// Request
use App\Http\Requests\ {
    RegisterRequest,
    LoginRequest,
    RefreshTokenRequest
};

// Resources
use App\Http\Resources\UserResource;

use App\DTO\ {
    LoginDTO,
    RegisterDTO,
    RefreshTokenDTO,
    ProfileDTO
};

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(
        UserService $userService,
    )
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request){
        $registerDTO = new RegisterDTO(
            $email = $request->input('email'),
            $name = $request->input('name'),
            $password = $request->input('password')
        );
        return response()->json([
            'success' => true,
            'message' => $this->userService->register($registerDTO)
        ], 201);
    }

    public function login(LoginRequest $request){
        $loginDTO = new LoginDTO(
            $email = $request->input('email'),
            $password = $request->input('password')
        );
        list($token, $refreshToken) = $this->userService->login($loginDTO);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 1,
            'refresh_token' => $refreshToken,
        ]);
    }

    public function profile(){
        $profileDTO = new ProfileDTO(
            $user_id = auth('api')->user()->id,
        );
        return response()->json([
            'success' => true,
            'message' => 'Success get profile',
            'data' => new UserResource($this->userService->profile($profileDTO))
        ], 201);
    }

    public function logout(){
        return response()->json([
            'success' => true,
            'message' => $this->userService->logout()
        ], 200);
    }

    public function refreshToken(RefreshTokenRequest $request){
        $refreshTokenDTO = new RefreshTokenDTO(
            $email = $request->input('email'),
            $refresh_token = $request->input('refresh_token')
        );
        list($token, $refreshToken) = $this->userService->refreshToken($refreshTokenDTO);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 1,
            'refresh_token' => $refreshToken,
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request){
        $forgotPasswordDTO = new ForgotPasswordDTO(
            $email = $request->input('email'),
        );
        return response()->json([
            'success' => true,
            'message' => $this->userService->forgotPassword($forgotPasswordDTO)
        ], 201);
    }

    public function changePassword(ChangePasswordRequest $request){
        $changePasswordDTO = new ChangePasswordDTO(
            $user_id = auth('api')->user()->id,
            $old_password = $request->input('old_password'),
            $new_password = $request->input('new_password')
        );
        list($token, $refreshToken) = $this->userService->changePassword($changePasswordDTO);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 1,
            'refresh_token' => $refreshToken,
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $resetPasswordDTO = new ResetPasswordDTO(
            $api_token = $request->input('api_token'),
            $password = $request->input('password'),
        );
        return response()->json([
            'success' => true,
            'message' => $this->userService->resetPassword($resetPasswordDTO)
        ], 201);
    }
}