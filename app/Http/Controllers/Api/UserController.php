<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function signup(Request $request) {
        $validator = Validator::make($request->all(), [
                    'userName' => 'required',
                    'userEmail' => 'required',
                    'userPassword' => 'required',
                    'userLocation' => 'required',
                    'userFavVerse' => 'required',
                    'userJob' => 'required',
                    'userStatus' => 'required',
                    'deviceType' => 'required',
                    'deviceToken' => 'required'
        ]);
        if ($validator->fails()) {
            return buildResponse('error', "Fields Validation Errors.", buildResponseError());
        }
        $user = new User();
        $isExist = $user->where(['email' => $request->input('userEmail'), 'status' => 'Active', 'user_type' => 'User'])->first();
        if (!$isExist) {
            if ($request->hasFile('profilePicture')) {
                $this->validate($request, [
                    'profilePicture' => 'mimes:jpg,jpeg,png,tiff,bmp',
                ]);

                $file = $request->profilePicture;
                $file_name = str_replace(' ', '', strtolower($request->input('userName'))) . '.' . $file->getClientOriginalExtension();
                $path = public_path() . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'profile_pictures' . DIRECTORY_SEPARATOR;
                $file->move($path, $file_name);
                $filenametostore = $file_name;
            } else {
                $filenametostore = '';
            }
            $user->name = $request->input('userName');
            $user->email = $request->input('userEmail');
            $user->location = $request->input('userLocation');
            $user->favourite_verse = $request->input('userFavVerse');
            $user->job = $request->input('userJob');
            $user->current_status = $request->input('userStatus');
            $user->profile_pic = $filenametostore;
            $user->device_type = $request->input('deviceType');
            $user->device_token = $request->input('deviceToken');
            $user->password = bcrypt($request->input('userPassword'));
            $user->save();
            $lastId = $user->id;
            if ($lastId) {
                $userDetail = $user->where('id', $lastId)->first();
                $userArray = array(
                    'userId' => $userDetail->id,
                    'userName' => $userDetail->name,
                    'userEmail' => $userDetail->email,
                    'userLocation' => $userDetail->location,
                    'userFavVerse' => $userDetail->favourite_verse,
                    'userJob' => $userDetail->job,
                    'userStatus' => $userDetail->current_status,
                    'profilePic' => url('/').'/public/uploads/profile_pictures/'.$userDetail->profile_pic
                );
                return buildResponse('success', "User Signup Successfully.", $userArray);
            } else {
                return buildResponse('error', "Signup Failed! There was an Error.", buildResponseError());
            }
        } else {
            return buildResponse('error', "User Against this email Already Exist.", buildResponseError());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
                    'email' => 'required',
                    'password' => 'required',
                    'deviceType' => 'required',
                    'deviceToken' => 'required'
        ]);
        if ($validator->fails()) {
            return buildResponse('error', "Fields Validation Errors.", buildResponseError());
        }
        $credentials = request(['email', 'password']);
        $credentials['user_type'] = 'User';
        if (Auth::attempt($credentials)) { //<---
            $userArray = array(
                'userId' => Auth::User()->id,
                'userName' => Auth::User()->name,
                'userEmail' => Auth::User()->email,
                'userLocation' =>  Auth::User()->location,
                'userFavVerse' =>  Auth::User()->favourite_verse,
                'userJob' =>  Auth::User()->job,
                'userStatus' =>  Auth::User()->current_status,
                'profilePic' => url('/').'/public/uploads/profile_pictures/'.Auth::User()->profile_pic
            );
            $user = new User();
            $token = $user->createToken(request('deviceType'))->accessToken;
            $userArray['token'] = $token;
            $update_device_token_type = array(
                'device_token' => $request->input('deviceToken'),
                'device_type' => $request->input('deviceType'),
            );

            $updated = $user->where('id', Auth::User()->id)->update($update_device_token_type);
            return buildResponse('success', "User Login Successfully.", $userArray);
        } else {
            return buildResponse('error', "Login Failed! Email or Password Incorrect.", buildResponseError());
        }
    }

    public function forgetPassword(Request $request) {
        $validator = Validator::make($request->all(), [
                    'email' => 'required',
        ]);
        if ($validator->fails()) {
            return buildResponse('error', "Fields Validation Errors.", buildResponseError());
        }

        $user = new User();
        $isExist = $user->where(['email' => $request->input('email'), 'status' => 'Active', 'user_type' => 'User'])->first();
        if ($isExist) {
            $digits = 4;
            $verifyToken = rand(pow(10, $digits - 1), pow(10, $digits) - 1);
            $update = array(
                'verification_code' => $verifyToken
            );
            $updated = $user->where('id', $isExist->id)->update($update);
            if ($updated) {
                sendEmail($isExist->name, $verifyToken, $request->input('email'));
            }
            return buildResponse('success', "Verification Code sent to your Email Address.", buildResponseError());
        } else {
            return buildResponse('error', "User Against this Email Not Found.", buildResponseError());
        }
    }

    public function verifyCode(Request $request) {
        $validator = Validator::make($request->all(), [
                    'verificationCode' => 'required',
        ]);
        if ($validator->fails()) {
            return buildResponse('error', "Fields Validation Errors.", buildResponseError());
        }

        $user = new User();
        $isExist = $user->where(['verification_code' => $request->input('verificationCode'), 'status' => 'Active', 'user_type' => 'User'])->first();
        if ($isExist) {
            $userArray = array(
                'userId' => $isExist->id,
                'userName' => $isExist->name,
                'userEmail' => $isExist->email,
            );
            return buildResponse('success', "Code Successfully Matched.", $userArray);
        } else {
            return buildResponse('error', "User Against this Email Not Found.", buildResponseError());
        }
    }

    public function resetPassword(Request $request) {
        $validator = Validator::make($request->all(), [
                    'userId' => 'required',
                    'password' => 'required',
        ]);
        if ($validator->fails()) {
            return buildResponse('error', "Fields Validation Errors.", buildResponseError());
        }

        $user = new User();
        $password = bcrypt($request->input('password'));

        $data = array(
            'password' => $password
        );
        $success = $user->where('id', $request->input('userId'))->update($data);
        if ($success) {
            return buildResponse('success', "Password Reset Successfully.", buildResponseError());
        } else {
            return buildResponse('error', "Password Not Reset Successfully.", buildResponseError());
        }
    }

    public function getProfilePic() {
        $validator = Validator::make($request->all(), [
                    'userId' => 'required',
        ]);
        if ($validator->fails()) {
            return buildResponse('error', "Fields Validation Errors.", buildResponseError());
        }

        $user = new User();
        $userData = $user->where(['id' => $request->input('userId'), 'status' => getConstant('STATUS_ACTIVE')])->first();
        if ($userData) {
            $profilePic = $userData->profile_pic;
            $returnArr = array(
                'profilePic' => $profilePic
            );
            return buildResponse('success', "Profile Pic.", $returnArr);
        } else {
            return buildResponse('error', "User Not Exist.", buildResponseError());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
