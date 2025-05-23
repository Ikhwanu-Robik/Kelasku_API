<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\JSONAPIResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        } catch (Exception $e) {
            return $this->error('User tidak ditemukan', 404);
        }

        return $this->success($user, 'Berhasil mendapatkan data user');
    }

    public function whoami(Request $request) {
        try {
            $user = $request->user();

            return $this->success($user, "Berhasil mendapatkan data diri");
        }
        catch (Exception $e) {
            return $this->error("Gagal mendapatkan data diri");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'motto' => 'sometimes',
            'photo' => 'sometimes|image|max:2048|dimensions:ratio=1/1',
            'school' => 'required|exists:schools,id'
        ]);

        if ($id != Auth::id()) {
            return $this->error("Kamu tidak boleh merubah profil orang lain!", 403);
        }

        if (isset($validated['photo'])) {
            $path_name = $request->file('photo')->storePublicly('profile_photos');
        }

        $user = User::find($id);
        $user->name = $validated['firstname'] . " " . $validated['lastname'];
        $user->studentProfile->school_id = $validated['school'];
        $user->studentProfile->motto = $validated['motto'] ?? NULL;
        $user->studentProfile->photo = $path_name ?? NULL;
        $user->studentProfile->save();
        $user->save();

        return $this->success(null, "Profil berhasil diubah");
    }

    /**
     * Update the specified user's password in storage
     */
    public function updatePassword(Request $request, string $id)
    {
        $validated = $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::find($id);
        $changer = User::find(Auth::id());

        if ($user != $changer) {
            return $this->error('Kamu tidak boleh mengganti password orang lain!', 403);
        }

        if (!Hash::check($validated['old_password'], $user->password))
        {
            return $this->error('Password lama salah', 403);
        }

        $user->password = $validated['password'];
        $user->save();

        return $this->success(null, 'Password berhasil diganti');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->deleteOrFail();

            return $this->success(null, "Berhasil menghapus user");
        } catch (Exception $e) {
            return $this->error("Gagal menghapus user");
        }
    }
 
    public function adminUpdate(Request $request, string $id) {
        $validated = $request->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'school_id' => 'required|exists:schools,id',
            'photo' => 'sometimes|image|max:2048|dimensions:ratio=1/1',
            'motto' => 'sometimes|string',
            'password' => 'required|min:8'
        ]);

        if (isset($validated['photo'])) {
            $path = $request->file('photo')->storePublicly('profile_photos');
        }

        try {
            $user = User::findOrFail($id);
            $user->name = $validated['firstname'] . " " . $validated['lastname'];
            $user->studentProfile->school_id = $validated['school_id'];
            if (isset($validated['photo'])) {
                $user->studentProfile->photo = $path ?? NULL;
            }
            if (isset($validated['motto'])) {
                $user->studentProfile->motto = $validated['motto'] ?? NULL;
            }
            $user->studentProfile->saveOrFail();
            $user->password = $validated['password'];
            $user->saveOrFail();
    
            return $this->success(null, "Berhasil mengubah data user");
        } catch (Exception $e) {
            return $this->error('Gagal mengubah data user');
        }
    } 
}
