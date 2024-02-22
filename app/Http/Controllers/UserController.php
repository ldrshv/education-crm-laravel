<?php

namespace App\Http\Controllers;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Fluent;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\User;

class UserController extends Controller
{
    public function update(Request $request) 
    {
        Validator::make($request->all(), [
            'name' => 'required',
        ])->validate();
        
        $user = User::findOrFail(auth()->user()->id);
        $user->update($request->only((new User)->getFillable()));

        return 'ok';
    }


    public function changePassword(Request $request)
    {
        Validator::make($request->all(), [
            'old_password' => ['required', function(string $attribute, mixed $value, Closure $fail) {
                if(!Hash::check($value, auth()->user()->password)) {
                    $fail('Неверный пароль.');
                }
            }], 
            'new_password' => 'required|confirmed|min:8',
        ])->validate();

        User::findOrFail(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return 'ok';
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $path = public_path('storage/avatars/');
        !is_dir($path) && mkdir($path, 0777, true);

        $name = auth()->user()->id . '.jpg';
        Image::make($request->file('avatar'))
            ->encode('jpg', 75)
            ->resize(300, 300)
            ->save($path . $name);
        
        return 'ok';
    }
}
