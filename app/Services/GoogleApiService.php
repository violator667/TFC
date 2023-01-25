<?php


namespace App\Services;


use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Exception;

class GoogleApiService
{
    public function googleCallback() :object
    {
        try {
            $googleUser = $this->getGoogleUser();

            if ($googleUser->id) {
                Auth::login($googleUser->id);

                return redirect('tickets.list');
            } else {
                $newGoogleUser = $this->createGoogleUser($googleUser);
                Auth::login($newGoogleUser);

                return redirect('tickets.list');
            }

        } catch (Exception $e) {
            abort('401');
        }
    }

    private function getGoogleUser() :object
    {
       return User::where('google_id', Socialite::driver('google')->user())->first();
    }

    private function createGoogleUser($user) :object
    {
        return User::create(
            [
                'name' => $user->name,
                'email' => $user->email,
                'google_id'=> $user->id
            ]
        );
    }
}
