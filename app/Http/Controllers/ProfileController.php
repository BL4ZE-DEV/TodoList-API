<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function view()
    {
        $profile = User::where('userId' , Auth::user()->userId)->first();

        return response()->json([
            'message' => 'Success!',
            'data' => $profile
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = User::where('userId', Auth::user()->userId);

        $data = [
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'nickname' => $request->nickname ?? $user->nickname
        ];

        if($user->update($data))
        {
            return response()->json([
                'message' => 'Profile updated succsessfully !'
            ]);
        }
    }

    private function checkPassword($old_password)
    {
        $user = User::find(Auth::id());
    
        if($user && Hash::check($old_password, $user->password)){
            return true;
        }
        return false;
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|confirmed',
            'old_password' => 'required'
        ]);

        if($this->checkPassword($request->old_password)){
            $user = User::where('userId', Auth::user()->userId);
            $data = [
                'password' => Hash::make($request->new_password) ?? $user->password
            ];

            if($user->update($data)){
                return response()->json([
                    'message' => 'Password updated successfully'
                ]);
            }
        }
    }
}
 