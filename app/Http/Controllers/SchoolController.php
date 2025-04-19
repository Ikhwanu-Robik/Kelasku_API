<?php

namespace App\Http\Controllers;

use App\JSONAPIResponse;
use App\Models\School;
use Exception;
use Illuminate\Http\Request;

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
            $this->error('Server error', 500);
        }

        return $this->success(null, "Berhasil menambahkan sekolah");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
