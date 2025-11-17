<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Kavenegar\KavenegarApi;


class AuthController extends ApiController
{
    public function login()
    {
        request()->validate([
            'cell_phone' => ['required', 'regex:/^09[0-9]{9}$/'],
        ]);

        $user = User::where('cell_phone', request()->cell_phone)->first();
        $otp = rand(100000, 999999);
        $loginToken = Hash::make('DCDC');

        if ($user) {
            $user->update(['otp' => $otp, 'login_token' => $loginToken]);
        } else {
            $user = User::create([
                'cell_phone' => request()->cell_phone,
                'otp' => $otp,
                'login_token' => $loginToken,
            ]);
        }

        try {

            $sender = "2000660110";
            $receptor = "09222383670";
            $message = "کد ورود $otp";
            $api = new  KavenegarApi("7342314C6B366A644A6C723439567439626B4B2B656C46536A78556C4333686A366A466B785752425466553D");
            $api->Send($sender, $receptor, $message);

        } catch (\Exception $e) {
            \Log::error('Kavenegar Error: ' . $e->getMessage());
            return $this->ErrorResponse('خطا در ارسال پیامک', 500);
        }

        return $this->SuccessResponse(['login_token' => $loginToken]);
    }


    public function checkOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|digits:6',
            'login_token' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        $user = User::where('login_token', $request->login_token)->first();

        if ($user && $user->otp == $request->otp) {
            $token = $user->createToken('myApp')->plainTextToken;

            return $this->SuccessResponse([
                'user' => $user,
                'token' => $token
            ]);

        } else {
            return $this->errorResponse(['otp' => ['کد ورود نادرست است']], 422);
        }
    }

    public function me()
    {
        return $this->SuccessResponse([new UserResource(auth()->user())]);
    }

    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login_token' => 'required|string'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }
        $user = User::where('login_token', $request->login_token)->first();
        $otp = rand(100000, 999999);
        $loginToken = Hash::make('DCDC');
        if ($user) {
            $user->update(['otp' => $otp, 'login_token' => $loginToken]);
        } else {
            return $this->ErrorResponse(['login_token' => ['یافت نشد ']]);
        }
        $sender = "2000660110";
        $receptor = "09222383670";
        $message = "کد ورود مشمدعلی  $otp";
        $api = new  KavenegarApi("7342314C6B366A644A6C723439567439626B4B2B656C46536A78556C4333686A366A466B785752425466553D");
        $api->Send($sender, $receptor, $message);
        return $this->SuccessResponse(['login_token' => $loginToken]);
    }

    public function logout(){
      auth()->user()->tokens()->delete();
      return $this->SuccessResponse('Logout Successfully');
    }
}
