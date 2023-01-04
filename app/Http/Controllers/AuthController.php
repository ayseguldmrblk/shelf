<?php

/** @noinspection PhpUndefinedClassInspection */

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Book;
use App\Models\Address;
use App\Models\PasswordReset;
use Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        if (User::where('email', $request->email)->first() != null) {
            return response()->json([
                'result' => false,
                'message' => 'User already exists.',
                'user_id' => 0
            ], 201);
        }


            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

        $user->verification_code = "123456";

        $user->save();

        //create token
        $user->createToken('tokens')->plainTextToken;

        return response()->json([
            'result' => true,
            'message' => 'Registration Successful. Please verify and log in to your account.',
            'user_id' => $user->id
        ], 201);
    }

    public function resendCode(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $user->verification_code = "123456";

        if ($request->verify_by == 'email') {
            $user->notify(new AppEmailVerificationNotification());
        } else {
            $otpController = new OTPVerificationController();
            $otpController->send_code($user);
        }

        $user->save();

        return response()->json([
            'result' => true,
            'message' => translate('Verification code is sent again'),
        ], 200);
    }

    public function confirmCode(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();

        if ($user->verification_code == $request->verification_code) {
            $user->email_verified_at = date('Y-m-d H:i:s');
            $user->verification_code = null;
            $user->save();
            return response()->json([
                'result' => true,
                'message' => 'Your account is now verified.Please login',
            ], 200);
        } else {
            return response()->json([
                'result' => false,
                'message' => 'Code does not match, you can request for resending the code',
            ], 200);
        }
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user != null) {
            if (Hash::check($request->password, $user->password)) {

                if ($user->email_verified_at == null) {
                    return response()->json(['result' => false, 'message' => 'Please verify your account', 'user' => null], 401);
                }
                return $this->loginSuccess($user);
            } else {
                return response()->json(['result' => false, 'message' => 'Password is incorrect', 'user' => null], 401);
            }
        } else {
            return response()->json(['result' => false, 'message' => 'User not found', 'user' => null], 401);
        }
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function logout(Request $request)
    {

        $user = auth()->user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        return response()->json([
            'result' => true,
            'message' => 'Successfully logged out'
        ]);
    }

    protected function loginSuccess($user)
    {
        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'result' => true,
            'message' => 'Successfully logged in',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => null,
            'user' => [
                'id' => $user->id,
                'is_manager' => $user->is_manager,
                'is_superuser' => $user->is_superuser,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }

    public function delete($id)
    {
        Book::where('donor',$id)->delete();
        User::where('id', $id)->delete();
        Address::where('user_id', $id)->delete();
    }

    public function update($id,Request $request)
    {
       $user = User::where('id', $id)->first();
       $user->name = $request->name;
       $user->password = bcrypt($request->password);
       $user->email = $request->email;
       $user->save();
       return response()->json($user, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function users()
    {
        $users = User::get();
        return response()->json($users, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
    }

    public function setAdmin(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        if($user->is_superuser==1){
            $user->is_superuser=0;
        }else{
            $user->is_superuser=1;
        }
        $user->save();
    }

    public function resetPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if($user){
            $reset = new ResetPassword;
            $reset->email = $request->email;
            $reset->token = "123456";
            return response()->json(['status'=>true, 'user_id'=>$user->id], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
        }else{
            return response()->json(['status'=>false, 'user_id'=>0], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
        JSON_UNESCAPED_UNICODE);
        }
    }

    public function setPassword(Request $request)
    {
        $user = User::where('id', $request->user_id)->where('email_verified_at', '!=', null)->first();
        if(!$user){
            return response()->json(['status'=>false, 'message'=> 'Verified user not found'], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE);
        }

            $token = ResetPassword::where('email', $user->email)->last();
            if($request->reset_code==$token->token){
                $user->password = bcrypt($request->password);
                $user->save();
                return response()->json(['status'=>true, 'message'=> 'Successfully'], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE);
            }else{
                return response()->json(['status'=>false, 'message'=> 'The entered code is incorrect'], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE);
            }

    }


    public function setManager(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        if($user->is_manager==1){
            $user->is_manager=0;
        }else{
            $user->is_manager=1;
        }
        $user->save();
    }

}
