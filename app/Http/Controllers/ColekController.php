<?php

namespace App\Http\Controllers;

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
        $user->notify(new ColekNotification(User::firstWhere('id', '=', Auth::id())));

        return $this->success(null, "Kamu Telah Mencolek Teman Mu!");
    }
}