<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

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
        return view('home');
    }

    public function users()
    {

        $users = User::all();

        return view('users.index', compact('users', $users));

    }


    protected function createUser(Request $request)
    {
         User::create([

            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role
        ]);

        return redirect()->back()->with('success', 'User successfully added.');


    }


    protected function editUser(Request $request)
    {

        $obj_user = User::find($request->userId);
        $obj_user->password = Hash::make($request->password);
        $obj_user->save();

        return redirect()->back();

    }


    public function deleteUser(Request $request)
    {

        User::find($request->userId)->delete();

        Session::flash('success', 'User deleted!');


    }

}
