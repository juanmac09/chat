<?php

namespace App\Http\Middleware;

use App\Interfaces\IMiddlewareUserManagement;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class JwtMiddleware
{
    public $userService;
    public function __construct(IMiddlewareUserManagement $userService) {
        $this->userService = $userService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            
            $token = JWTAuth::parseToken();
            $payload =  $token->getPayload();
            if(!isset($payload['rrhh_id']) || !isset($payload['rrhh']['name'])) {
                return response()->json(['error' => 'Token is invalid'], 401);
            }
            $user = $this -> userService -> getUserForRrhh_id($payload['rrhh_id']);
            if (!$user) {
                $user = $this -> userService -> createUser(['name'=>$payload['rrhh']['name'],'rrhh_id'=>$payload['rrhh_id']]);
            } 
            Auth::login($user);
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
