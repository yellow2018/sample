<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function create(){
        return view('users.create');
    }

    public function show(User $user){
        return view('users.show', compact('user'));
    }

    public function store(Request $requests){
        $this->validate($requests,[
            'name'      =>  'required|max:50',
            'email'     =>  'required|unique:users|max:255',
            'password'  =>  'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name'      =>  $requests->name,
            'email'     =>  $requests->email,
            'password'  =>  bcrypt($requests->password)
        ]);

        session()->flash('success','注册成功！');
        return redirect()->route('users.show',[$user]);
    }
}
