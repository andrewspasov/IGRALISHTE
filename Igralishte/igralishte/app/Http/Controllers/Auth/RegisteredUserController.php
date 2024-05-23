<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    public function showRegistrationFormStepOne()
    {
        return view('auth.register_step_one');
    }

    public function processRegistrationStepOne(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ], [
            'email.required' => 'Полето за е-пошта е задолжително.',
            'email.email' => 'Е-поштата мора да биде валидна адреса.',
            'email.unique' => 'Е-поштата веќе е земена.',
            'password.required' => 'Полето за лозинка е задолжително.',
            'password.min' => 'Лозинката мора да има барем 8 карактери.',
        ]);

        session([
            'registration.email' => $validatedData['email'],
            'registration.password' => $validatedData['password'],
        ]);

        return redirect()->route('register.step.two');
    }

    public function showRegistrationFormStepTwo()
    {
        if (!session()->has('registration.email') || !session()->has('registration.password')) {
            return redirect()->route('register.step.one')->withErrors(['msg' => 'Ве молиме комплетирајте го почетниот чекор за регистрација.']);
        }

        return view('auth.register_step_two');
    }

    public function processRegistrationStepTwo(Request $request)
    {
        Log::debug('Processing Step Two');

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'password' => 'required|min:8|confirmed',
            'email' => 'required|email|unique:users',
            'wants_promo_emails' => 'sometimes|boolean',
        ], [
            'first_name.required' => 'Полето за име е задолжително.',
            'first_name.string' => 'Името мора да биде текст.',
            'first_name.max' => 'Името не смее да биде подолго од 50 карактери.',
            'last_name.required' => 'Полето за презиме е задолжително.',
            'last_name.string' => 'Презимето мора да биде текст.',
            'last_name.max' => 'Презимето не смее да биде подолго од 50 карактери.',
            'password.required' => 'Полето за лозинка е задолжително.',
            'password.min' => 'Лозинката мора да има барем 8 карактери.',
            'password.confirmed' => 'Потврдата за лозинката не се совпаѓа.',
            'email.required' => 'Полето за е-пошта е задолжително.',
            'email.email' => 'Е-поштата мора да биде валидна адреса.',
            'email.unique' => 'Е-поштата веќе е земена.',
            'wants_promo_emails.boolean' => 'Полето за промотивни е-пошти мора да биде вистина или невистина.',
        ]);

        $wantsPromoEmails = filter_var($request->input('wants_promo_emails', false), FILTER_VALIDATE_BOOLEAN);

        $registrationData = session('registration');
        $combinedData = array_merge($registrationData, $validatedData, ['wants_promo_emails' => $wantsPromoEmails]);

        session(['registration_data' => $combinedData]);

        return redirect()->route('register.step.three');
    }

    public function showRegistrationFormStepThree()
    {
        if (!session()->has('registration_data')) {
            return redirect()->route('register.step.two')->withErrors(['msg' => 'Ве молиме завршетите го предходниот чекор за регистрација.']);
        }
            return view('auth.register_step_three');
    }

    
    public function processRegistrationStepThree(Request $request)
    {
        $validatedData = $request->validate([
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'bio' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
        ], [
            'profile_photo.image' => 'Фотографијата за профилот мора да биде слика.',
            'profile_photo.mimes' => 'Фотографијата за профилот мора да биде во формат: jpg, jpeg, png.',
            'profile_photo.max' => 'Фотографијата за профилот не смее да биде поголема од 2048 килобајти.',
            'bio.string' => 'Биографијата мора да биде текст.',
            'bio.max' => 'Биографијата не смее да биде подолга од 1000 карактери.',
            'address.string' => 'Адресата мора да биде текст.',
            'address.max' => 'Адресата не смее да биде подолга од 255 карактери.',
            'phone_number.string' => 'Телефонскиот број мора да биде текст.',
            'phone_number.max' => 'Телефонскиот број не смее да биде подолг од 20 карактери.',
        ]);

        $registrationData = session('registration_data');
        if (!$registrationData) {
            return redirect()->route('register.step.one')->withErrors(['msg' => 'Иницијалните податоци за регистрација недостасуваат. Ве молиме започнете одново.']);
        }

        $combinedData = array_merge($registrationData, $validatedData);

        $baseUsername = strtolower($combinedData['first_name'] . $combinedData['last_name']);
        $username = $baseUsername;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        $relativePath = null;
        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $path = $request->file('profile_photo')->store('public/profile_photos');
            $relativePath = substr($path, strlen('public/'));
        }

        $user = User::create([
            'email' => $combinedData['email'],
            'password' => Hash::make($combinedData['password']),
            'first_name' => $combinedData['first_name'],
            'last_name' => $combinedData['last_name'],
            'username' => $username,
            'role' => 'user',
            'profile_photo' => $relativePath,
            'bio' => $combinedData['bio'] ?? null,
            'address' => $combinedData['address'] ?? null,
            'phone_number' => $combinedData['phone_number'] ?? null,
            'wants_promo_emails' => $combinedData['wants_promo_emails'],
        ]);

        Auth::login($user);
        session()->forget('registration_data');

        return redirect(RouteServiceProvider::HOME)->with('success', 'Регистрацијата е комплетна.');
    }

    public function skipRegistration()
    {
        $registrationData = session('registration_data');

        if (!$registrationData) {
            return redirect()->route('register.step.one')->withErrors(['msg' => 'Ве молиме започнете го процесот на регистрација повторно.']);
        }

        $baseUsername = strtolower($registrationData['first_name'] . $registrationData['last_name']);
        $username = $baseUsername;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        $user = User::create([
            'email' => $registrationData['email'],
            'password' => Hash::make($registrationData['password']),
            'first_name' => $registrationData['first_name'],
            'last_name' => $registrationData['last_name'],
            'username' => $username,
            'role' => 'user',
            'wants_promo_emails' => $registrationData['wants_promo_emails'],
        ]);
        Auth::login($user);
        session()->forget('registration_data');

        return redirect(RouteServiceProvider::HOME)->with('success', 'Регистрацијата е комплетна.');
    }
}

