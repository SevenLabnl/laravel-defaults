<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\Api\User\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): UserResource
    {
        $data = $request->validated();

        $data['password'] = Hash::make($data['password']);

        $user = User::query()
            ->create($data);

        event(new Registered($user));

        $token = $user->createToken('auth')
            ->plainTextToken;

        return new UserResource($user, $token);
    }
}
