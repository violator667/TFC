<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\GoogleApiService;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    private object $googleApiService;

    public function __construct(GoogleApiService $service)
    {
        $this->googleApiService = $service;
    }
    public function redirectToGoogle() :object
    {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback() :object
    {
        return $this->googleApiService->googleCallback();
    }
}
