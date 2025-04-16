<?php

namespace App\Http\Controllers;

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
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logout successful');
    }
}
