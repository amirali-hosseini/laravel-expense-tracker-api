<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;

class ProfileController extends Controller
{
    public function profile()
    {
        return $this->jsonResponse(['data' => new UserResource(auth()->user())]);
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        auth()->user()->update($request->validated());

        return $this->jsonResponse(['message' => 'Profile has been updated.']);
    }
}
