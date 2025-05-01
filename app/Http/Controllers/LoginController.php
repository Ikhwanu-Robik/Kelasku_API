<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\JSONAPIResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use JSONAPIResponse;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $validated = $request->validate([
                'phone' => 'required|phone:phone_country|exists:users,phone',
                'phone_country' => 'required_with:phone',
                'password' => 'required',
                'fcm_token' => 'sometimes'
            ]);
        } catch (ValidationException $e) {
            return $this->error('Validasi gagal', 422, $e->errors());
        }

        $credentials = [
            'phone' => $validated['phone'],
            'password' => $validated['password']
        ];

        if (Auth::attempt($credentials)) {
            $user = $request->user();

            $token = $user->createToken('user_token', ["*"], now()->addDays(3));

            if (isset($validated['fcm_token'])) {
                $userModel = User::find(Auth::id());
                $userModel->fcm_token = $validated['fcm_token'];
                $userModel->save();
            }

            return $this->success(['Token' => $token->plainTextToken], 'Login successful');
        } else {
            return $this->error('Login failed', 403);
        }
    }
}