<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Exception
use App\Exceptions\CustomException;

USE Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        app('auth')->setDefaultDriver('api');
        
        $token =  JWTAuth::getToken();

        try {
            $user = JWTAuth::authenticate($token);
        } catch (\Exception $e) {
            throw new CustomException("Token is invalid", 401);
        }

        if (!$user) {
            throw new CustomException("User not found!", 404);
        }

        if(!$user->is_active)
            throw new CustomException("Your account is deactivated.", 401);

        if (!$user->email_verified_at)
            throw new CustomException("Your account has not been verified.", 401);

        $iat = Carbon::parse(JWTAuth::getPayload(JWTAuth::getToken())->toArray()['iat'])->tz('Asia/Jakarta');
        if($iat){
            if($iat < $user->update_password_at){
                throw new CustomException("Unauthorized", 401);
            }
        }

        return $next($request);
    }
}
