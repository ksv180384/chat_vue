<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(){}

    public function login(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->noContent();

        /*
        $credentials = $request->only(['email', 'password']);

        $token = JWTAuth::attempt($credentials);
        if (!$token) {
            //return response()->json(['message' => 'Неверный логин или пароль'], 401);
            throw ValidationException::withMessages(['error_auth' => ['Неверный логин или пароль']]);
        }

        return response()->json(array_merge(
            $this->respondWithToken($token)->getOriginalContent(),
            ['user' => Auth::user()->only(['id', 'name', 'avatar', 'avatar_src'])]
        ));
        */
    }

    public function registration(RegistrationRequest $request){

        $user = User::create(array_merge(
            $request->validated(),
            ['password' => bcrypt($request->password)]
        ));

        $token = Auth::login($user);

        return response()->json(array_merge(
            $this->respondWithToken($token)->getOriginalContent(),
            [
                'message' => 'Вы успешно зарегистрировались.',
                'user' => Auth::user()->only(['id', 'name', 'avatar', 'avatar_src'])
            ]
        ));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }


    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        /*
        try {
            return $this->respondWithToken(auth()->refresh());
        }catch (JWTException $exception){
            return response()->json([
                'message' => $exception->getMessage(),
            ], 401);
        }
        */
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
