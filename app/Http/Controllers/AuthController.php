<?php
namespace App\Http\Controllers;

use App\Http\Controllers\ApiResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function loginView(Request $request)
    {

        return view('auth.login');
    }
    public function register(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'username' => 'required|string|unique:users',
                'password' => 'required|string|min:6',
            ]);

            // Buat user baru
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
            ]);

            return ApiResponse::success($user, "User registered successfully", 201);
        } catch (ValidationException $e) {
            return ApiResponse::error($e->errors(), 422);
        } catch (\Exception $e) {
            return ApiResponse::error("Failed to register user", 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('username', 'password');

            if (! $token = JWTAuth::attempt($credentials)) {
                return ApiResponse::error("Invalid credentials", 401);
            }

            session(['jwt_token' => 'test_token']);
            session()->save();
            session()->all();

            return ApiResponse::success([
                'token' => $token,
                'user'  => auth()->user(),
            ], "Login successful");
        } catch (JWTException $e) {
            return ApiResponse::error("Could not create token", 500);
        } catch (\Exception $e) {
            return ApiResponse::error("An error occurred", 500);
        }
    }

    public function logout(Request $request)
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        session()->forget('jwt_token');

        return redirect()->route('login');
    }

}
