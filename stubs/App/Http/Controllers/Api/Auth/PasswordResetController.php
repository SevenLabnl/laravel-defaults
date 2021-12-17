<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\PasswordForgetRequest;
use App\Http\Requests\Api\Auth\PasswordResetRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasswordResetController extends Controller
{
    public function forget(PasswordForgetRequest $request)
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return Response::json([
                'message' => 'Password reset link sent',
            ]);
        }

        return Response::json([
            'message' => 'Password forget link could not be sent',
        ], 500);
    }

    public function reset(PasswordResetRequest $request)
    {
        $status = Password::reset(
            $request->validated(),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return Response::json([
                'message' => 'Password reset successfully',
            ]);
        }

        throw ValidationException::withMessages([
            'token' => [
                'The provided token in combination with the email is incorrect.'
            ],
        ]);
    }
}
