<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use Exception;
use App\Models\User;
use App\JSONAPIResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    use JSONAPIResponse;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $validated = $request->validate([
                'firstname' => 'required|string',
                'lastname' => 'required|string',
                'phone' => 'required|phone:phone_country|unique:users,phone',
                'phone_country' => 'required_with:phone',
                'school' => 'required|exists:schools,id',
                'password' => 'required|min:8|confirmed',
            ]);
        } catch (ValidationException $e) {
            return $this->error('Validasi gagal', 422, $e->errors());
        }

        try {
            $user = User::create([
                'name' => $validated['firstname'] . " " . $validated['lastname'],
                'phone_country' => $validated['phone_country'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
            ]);

            StudentProfile::create([
                'user_id' => $user->id,
                'school_id' => $validated['school'],
            ]);
        } catch (Exception $e) {
            return $this->error('Gagal membuat akun', 500);
        }

        return $this->success(null, 'Berhasil register aplikasi Kelasku', 200);
    }
}