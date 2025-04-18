<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\JSONAPIResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ColekNotification;

class ColekController extends Controller
{
    use JSONAPIResponse;

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, User $user)
    {
        try {
            $user->notify(new ColekNotification(User::firstWhere('id', '=', Auth::id())));
        } catch (Exception $e) {
            return $this->error('Gagal mencolek teman', 500);
        }

        return $this->success(null, "Kamu Telah Mencolek Teman Mu!");
    }
}