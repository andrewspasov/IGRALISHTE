<?php

namespace App\Http\Controllers;

use \Log;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AdminProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    public function update(UpdateUserProfileRequest $request)
    {
        $user = Auth::user();

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;

        if ($request->hasFile('profile_photo') && $request->file('profile_photo')->isValid()) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo = $path;
        }

        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Профилот е апдејтиран успешно.');
    }


    public function showPasswordForm()
    {
        return view('admin.change-password');
    }

    public function newPassword(Request $request)
    {
        $customMessages = [
            'password.required' => 'Полето за лозинка е задолжително.',
            'password.string' => 'Лозинката мора да биде текст.',
            'password.min' => 'Лозинката мора да содржи најмалку 8 карактери.',
            'password.confirmed' => 'Лозинките не се совпаѓаат.',
        ];

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], $customMessages);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->update();

        return redirect()->route('password.newShow')->with('success', 'Вашата лозинка е успешно променета.');
    }


}
