<?php

namespace App\Http\Controllers;

use App\JSONAPIResponse;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use JSONAPIResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = null;
        try {
            $user = User::findOrFail($id);
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
