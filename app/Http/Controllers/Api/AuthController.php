<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Jobs\SendCodeJob;
use App\Models\User;
use App\Models\VerificationCode;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    /**
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $request->validated();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        if (!$user) {
            return $this->errorResponse([], 'User Registration failed', 500);
        }

        $code = rand(100000, 999999);
        // Dispatch the job to send the verification code using queue
        SendCodeJob::dispatchSync($user->email, $code);

        return $this->successResponse(
            [
                'user' => new UserResource($user),
            ],
            'User registered successfully. Please check your email for the verification code.',
            201
        );
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!auth()->attempt($request->only('email', 'password'))) {
            return $this->errorResponse([], 'Invalid credentials', 401);
        }

        if (auth()->user()->email_verified_at === null) {
            return $this->errorResponse([], 'Please verify your email before logging in.', 403);
        }
        $user = auth()->user();
        // generate a new token for the user
        $token = $user->createToken('auth_token')->plainTextToken;
        return $this->successResponse(
            [
                'user' => new UserResource($user),
                'token' => $token
            ],
            'Login successful.',
            200
        );
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        $user->tokens()->delete();

        return $this->successResponse([], 'Logged out successfully.', 200);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|integer',
        ]);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->errorResponse([], 'User not found', 404);
        }

        if ($user->email_verified_at !== null) {
            return $this->successResponse([], 'Email already verified');
        }

        $verificationCode = VerificationCode::where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if ($verificationCode == $request->code  || $verificationCode->expires_at < now()) {
            return $this->errorResponse([], 'Invalid verification code or expired', 400);
        }

        $user->email_verified_at = now();
        $user->save();

        $verificationCode->is_used = true;
        $verificationCode->save();

        return $this->successResponse([], 'Email verified successfully.', 200);
    }

    public function resendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->errorResponse([], 'User not found', 404);
        }

        if ($user->email_verified_at !== null) {
            return $this->successResponse([], 'Email already verified');
        }

        $code = rand(100000, 999999);
        // Dispatch the job to send the verification code using queue
        SendCodeJob::dispatchSync($user->email, $code);

        return $this->successResponse([], 'Verification code resent successfully.', 200);
    }
}
