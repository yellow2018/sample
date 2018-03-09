<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    public function create(){
        return view('sessions.create');
    }

    public function store(Request $request){
        $credentials = $this->validate($request,[
            'email'     =>  'required|email|max:255',
            'password'  =>  'required'
        ]);

        //判断用户登录
        if(Auth::attempt($credentials,$request->has('remember'))){
            session()->flash('success','登录成功！');
            return redirect()->route('users.show',[Auth::user()]);
        }else{
            session()->flash('danger','邮箱或者密码错误');
            return redirect()->back();
        }
    }

    public function destroy(){
        Auth::logout();
        session()->flash('success','成功退出！');
        return redirect('login');
    }
}
