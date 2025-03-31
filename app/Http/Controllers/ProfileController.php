<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function profile(): JsonResponse
    {
        return $this->jsonResponse(['data' => new UserResource(auth()->user())]);
    }

    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        auth()->user()->update($request->validated());

        return $this->jsonResponse(['message' => 'Profile has been updated.']);
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        auth()->user()->update(['password' => $request->password]);

        return $this->jsonResponse(['message' => 'Password has been updated.']);
    }
}
