<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SocialiteLoginController extends Controller
{

    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->redirectUrl(env('GOOGLE_REDIRECT_URI'))
            ->redirect();
    }


    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(env('GOOGLE_REDIRECT_URI'))
                ->user();

            $user = $this->findOrCreateUser($googleUser, 'google');
            Auth::login($user, true);

            return redirect('/user/dashboard');
        } catch (Exception $e) {
            Log::error('Google login error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors('Login via Google failed, please try again.');
        }
    }


    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')
            ->redirectUrl(env('FACEBOOK_REDIRECT_URI'))
            ->redirect();
    }



    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')
                ->redirectUrl(env('FACEBOOK_REDIRECT_URI'))
                ->user();

            $user = $this->findOrCreateUser($facebookUser, 'facebook');
            Auth::login($user, true);

            return redirect('/user/dashboard');
        } catch (Exception $e) {
            Log::error('Google login error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors('Login via Google failed, please try again.');
        }
    }

    protected function findOrCreateUser($providerUser, $provider)
    {
        $user = User::where('email', $providerUser->getEmail())->first();
    
        if (!$user) {
            $fullName = explode(' ', $providerUser->getName(), 2);
            $firstName = $fullName[0];
            $lastName = isset($fullName[1]) ? $fullName[1] : '';
    
            $baseUsername = strtolower($firstName) . '.' . strtolower($lastName);
            $baseUsername = preg_replace('/\s+/', '', $baseUsername);
    
            $username = $baseUsername;
            $counter = 1;
    
            // Check if the username already exists and increment the counter until finding a unique username
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }
    
            $user = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $providerUser->getEmail(),
                'username' => $username,
                'provider_id' => $providerUser->getId(),
                'provider' => $provider,
                'password' => Hash::make(uniqid()),
                'role' => 'user',
            ]);
        }
    
        return $user;
    }
    
    
}
