<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function __construct(){
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store','index']
        ]);

        $this->middleware('guest',[
            'only'  =>  ['create']
        ]);
    }

    public function index(){
        $users = User::paginate(10);
        return view('users.index',compact('users'));
    }

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

        Auth::login($user);
        session()->flash('success','注册成功！');
        return redirect()->route('users.show',[$user]);
    }

    public function edit(User $user){
        $this->authorize('update',$user);
        return view('users.edit',compact('user'));
    }

    public function update(User $user, Request $request){
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $this->authorize('update',$user);

        $data = [
            'name'  =>  $request->name
        ];
        if($request->password)
            $data['password'] = bcrypt($request->password);
        $user->update($data);
        session()->flash('success','更新成功！');

        return redirect()->route('users.show', $user->id);
    }

    public function destroy(User $user){
        $this->authorize('destroy',$user);
        $user->delete();
        session()->flash('success', '成功删除！');
        return back();
    }
}
