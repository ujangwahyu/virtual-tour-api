<?php
namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use App\Constants\StatusCode;

class JwtMiddleware{

    public function handle($request, Closure $next, $guard = null){
        $token = $request->header('Authorization');

        if(!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'message' => 'Token not provided.'
            ], 401, [], JSON_PRETTY_PRINT);
        }
        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
            
        } catch(ExpiredException $e) {
            return response()->json([
                'message' => 'Provided token is expired.'
            ], 400, [], JSON_PRETTY_PRINT);
        } catch(Exception $e) {
            return response()->json([
                'message' => 'An error while decoding token.'
            ], 400, [], JSON_PRETTY_PRINT);
        }
        $user = User::find($credentials->sub);
        // Now let's put the user in the request class so that you can grab it from there
        $request->auth = $user;
        
        return $next($request);
    }
}
