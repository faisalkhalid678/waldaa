<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;
class Admin_authentication extends Controller {

    function index() {
        $title = "Waldaa | Login";
        
        return view('admin/login', compact('title'));
    }

    function checklogin(Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:3'
        ]);
        $user_data = array(
            'email' => $request->get('email'),
            'password' => $request->get('password'),
        );
      
        
        if (Auth::attempt($user_data)) {
            return redirect('admin/');
        } else {
            return back()->with('error', 'Wrong Login Details');
        }
    }

    function successlogin() {
        return view('successlogin');
    }

    function logout() {
        Auth::logout();
        Session::flush();
        return redirect('admin/login');
    }

}
