<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        
        try {
            // $token = JWTAuth::parseToken();
            // if (!$token->check()) {
            //     return response()->json(['error' => 'Token is invalid'], 401);
            // }
            // $payload = $token->getPayload();
            // $rrhhId = $payload->get('rrhh')['ID_number'];
            // $user = User::where('rrhh_id', $rrhhId)->first();
            // if (!$user) {
            //     return response()->json(['error' => 'User not found'], 404);
            // }
            // Auth::login($user);
        } catch (TokenExpiredException $e) {
            return response()->json(['error' => 'Token has expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
        } catch (TokenBlacklistedException $e) {
            return response()->json(['error' => 'Token is blacklisted'], 401);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is not provided'], 401);
        }

        return $next($request);
    }
}
