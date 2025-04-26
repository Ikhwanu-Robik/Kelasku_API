<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\School;
use App\JSONAPIResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SchoolController extends Controller
{
    use JSONAPIResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $schools = School::all();

            if ($schools->count() == 0) {
                return $this->error('Tidak ada sekolah', 404);
            }

            return $this->success($schools, "Berhasil mendapatkan data sekolah");
        } catch (Exception $e) {
            return $this->error("Server error", 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required'
        ]);

        try {
            School::create(['name' => $validated['name']]);
        } catch (Exception $e) {
            return $this->error('Server error', 500);
        }

        return $this->success(null, "Berhasil menambahkan sekolah");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $school = School::findOrFail($id);

            return $this->success($school, "Berhasil mendapatkan data sekolah");
        } catch (Exception $e) {
            return $this->error("Server error", 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate(["name" => "required"]);
        
        try {
            $school = School::findOrFail($id);
            $school->name = $validated["name"];
            $school->saveOrFail();
    
            return $this->success(null, "Berhasil mengubah data sekolah");
        } catch (Exception $e) {
            return $this->error("Gagal mengubah data sekolah");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $school = School::findOrFail($id);

            $school->deleteOrFail();

            return $this->success(null, "Berhasil menghapus sekolah");
        } catch (Exception $e) {
            return $this->error('Gagal menghapus sekolah', 500);
        }
    }
}
