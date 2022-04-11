<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api_v1', ['except' => ['login', 'logout', 'registration']]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $remember = $request->remember == 'true';

        if (! $token = Auth::attempt($credentials, $remember) ) {
            return response()->json(['message' => 'Неверный логин или пароль'], 401);
        }

        return response()->json(array_merge(
            $this->respondWithToken($token)->getOriginalContent(),
            ['user' => Auth::user()->only(['id', 'name', 'avatar'])]
        ));
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
                'user' => Auth::user()->only(['id', 'name', 'avatar'])
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
        return $this->respondWithToken(auth()->refresh());
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
