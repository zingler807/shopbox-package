<?php
namespace Laracle\ShopBox\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Hash;
use Log;

class AuthController extends Controller
{


    public function login(Request $request)
    {
      $validatedData = $request->validate([
        'email'    => 'required',
        'password' => 'required',
       ]);

       if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
            return auth()->user();
        }

        return response()->json([
            'error' => 'Unauthenticated user',
            'code' => 401,
        ], 401);


    }

    public function register(Request $request)
    {


     $validatedData = $request->validate([
       'name' => 'unique:users|required',
       'email'    => 'unique:users|required',
       'password' => 'required',
      ]);

      $name = $request->name;
      $email    = $request->email;
      $password = $request->password;
      $token = Str::random(60);
      $user     = User::create(['name' => $name, 'email' => $email, 'api_token'=>$token,'password' => Hash::make($password)]);

      //event(new Laracle\Stateless\Events\RegisterUser($user));

      return $user;

    }

    public function forgot(Request $request)
    {
      $validatedData = $request->validate([
        'email'    => 'required'
       ]);

       $user = User::where('email',$request->email)->firstOrFail();
       $user->password_reset_token = Str::random(30);
       $user->save();

      // Send password reset email

    }

    public function resetPassword(Request $request)
    {
      $validatedData = $request->validate([
        'email'    => 'required',
        'password_reset_token'    => 'required|size:30',
        'password' => 'required'
       ]);

       $user = User::where('email',$request->email)->where('password_reset_token',$request->password_reset_token)->firstOrFail();

       $user->password_reset_token = NULL;
       $user->password = Hash::make($request->password);
       $user->save();
       return $user;
    }


    public function updatePassword(Request $request){

      $validatedData = $request->validate([
        'current_password'    => 'required',
        'new_password'    => 'required|min:6'
       ]);

       $user = $request->user();

       if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();
            return $user;
       }

       throw ValidationException::withMessages(['current_password' => 'Your current password is incorrect']);

    }



}
