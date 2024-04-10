<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Role;

use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // return view('home');
        if(Auth::check())
        {
            $user = Auth::user();

            $role_id = $user->role_id;

            if($role_id == 3)
            {
                return view('dashboard');
            }

            else if( $role_id == 4)
            {
                return view('admin.layouts.adminhome');
            }

            else
            {
                return redirect()->back();
            } 
        }else {
            return redirect()->route('login');
        }
    }
}
