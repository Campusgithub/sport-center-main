<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
/** @var \Laravel\Socialite\Contracts\Provider|\Laravel\Socialite\Two\AbstractProvider */

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
        ->with(['prompt' => 'select_account'])
        ->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'email_verified_at' => now(),
                'role' => 'admin',
                'Status' => 1,
                'CompanyCode' => 'SPORT001', // 👈 WAJIB DIISI
                'isDeleted' => 0,
                'CreatedBy' => 'system',
                'CreatedDate' => now(),
                'LastUpdatedBy' => 'system',
                'LastUpdatedDate' => now(),
                'password' => bcrypt(Str::random(16)), // random, biar aman
            ]
        );

        Auth::login($user);

        return redirect('/admin');
    }
}
