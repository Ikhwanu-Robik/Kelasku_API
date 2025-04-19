<?php

namespace App\Http\Controllers;

use Exception;
use App\JSONAPIResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    use JSONAPIResponse;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
        } catch (Exception $e) {
            return $this->error('Logout gagal');
        }

        return $this->success(null, 'Berhasil logout');
    }
}
