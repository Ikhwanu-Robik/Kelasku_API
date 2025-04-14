<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Propaganistas\LaravelPhone\PhoneNumber;

class RegisteredUserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|phone:phone_country|unique:users,telephone',
            'phone_country' => 'required_with:phone',
            'school' => 'required|exists:schools,name',
            'password' => 'required|min:8|confirmed',
        ]);

        return json_encode($validated);
    }
}