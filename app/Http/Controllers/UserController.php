<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\JSONAPIResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use JSONAPIResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::where('id', '!=', Auth::id())->get();

            if ($users->count() == 0) {
                return $this->error('Not found', 404);
            }

            return $this->success($users, "Data teman berhasil dimuat");
        } catch (Exception $e) {
            return $this->error('Data teman gagal dimuat', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = null;
        try {
            $user = User::findOrFail($id);

            if (!$user) {
                return $this->error('Not found', 404);
            }
        } catch (Exception $e) {
            return $this->error('User tidak ditemukan', 404);
        }

        return $this->success($user, 'Berhasil mendapatkan data user');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
