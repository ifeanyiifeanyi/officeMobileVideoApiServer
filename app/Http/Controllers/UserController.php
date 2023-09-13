<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helper\UserService;
use Illuminate\Support\Str;
use App\Helper\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {

        $token = Str::random(64);

        $userId = rand(1111, 9999);
        // set user account to 0 none active account
        $status = '0';

        $response = (new UserService($userId, $request->name, $request->username, $request->email, $request->password, $status))->register($request->devicename);

        $userId = $response['user_id'];

        // this token is for email verification
        $token = Str::random(64);

        $user = User::where('id', $userId)->first();
        $user->token = $token;
        $user->save();


        //if registration was successful send a email to verify account to activate it
        if ($response) {
            Mail::send('email.emailVerificationEmail', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject("Email verification");
            });
        }

        if (count($response) > 0) {
            return response()->json($response);
        } else {
            return response()->json([
                'error' => "registration failed",
            ], 404);
        }
    }

    public function verifyAccount($token)
    {
        $verifyUser = User::where('token', $token)->first();
        $message = "Sorry your mail could not be identified";

        if (!is_null($verifyUser)) {


            if ($verifyUser->status === 0) {
                $verifyUser->email_verified_at = now();
                $verifyUser->status = 1;
                $verifyUser->save();
                $message = "Your e-mail is verified. You can now login.";

                return response()->json([
                    'message' => $message
                ]);
            } else {
                $message = "Your e-mail is already verified. You can now login.";
                return response()->json([
                    'error' => $message
                ], 404);
            }
        }
        return redirect()->route('confirm.verify')->with('message', $message);
    }

    public function confirmVerify(Request $request)
    {
        return view('email.confirmVerify');
    }

    public function login(Request $request)
    {
        $response = (new LoginService($request->username, $request->password))->login();
        return response()->json($response);
    }


    public function updatePassword(Request $request)
    {

        $userId = $request->userId;
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $currentPassword = $request->currentPassword;
        $newPassword = $request->newPassword;
        $confirmPassword = $request->confirmPassword;

        $request->validate([
            'newPassword' => 'required|min:8|max:255',
        ]);
        if (!Hash::check($currentPassword, $user->password)) {

            return response()->json(['error' => 'Current password password is incorrect'], 400);
        }
        if (Hash::check($newPassword, $user->password)) {
            return response()->json(['error' => 'The new password must be different from the old one.'], 400);
        }

        // Update the user's password
        $user->password = Hash::make($newPassword);
        $user->save();

        return response()->json(['message' => 'Password updated successfully.'], 200);
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|min:6|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Email address is invalid'
            ], 422);
        }

        // check if user exists
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'Email address does not exist'
            ], 422);
        }

        // trying to send password reset link to user email
        $response = Password::sendResetLink($request->only('email'));

        if ($response) {
            return response()->json(['success' => true, 'message' => 'A password reset link has been sent to your email']);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Unable to send password reset link.',
            ], 500);
        }

    }

    public function updateProfile(Request $request)
    {
        $userId = $request->uId;
        $name = $request->name;
        $username = $request->username;

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:6|max:255|unique:users,username,' . $user->id,
            'name' => 'required|string|min:6|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()
            ], 422);
        }

        // Update user profile
        $user->name = $name;
        $user->username = $username;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

   

}