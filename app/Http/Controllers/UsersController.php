<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller {
    
    public function __construct() {
        //只允许登陆的用户相关的操作
        $this->middleware('auth',
            ['except' => ['show', 'create', 'store', 'index'],
            ]);
        //只允许为登陆的用户相关的操作
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }
    
    /**
     * 用户列表
     */
    public function index() {
        $users = User::paginate(6);
        return view('users.index', compact('users'));
    }
    
    /**
     * 用户注册
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create() {
        return view('users.create');
    }
    
    /**
     * 用户个人信息展示页
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user) {
        return view('users.show', compact('user'));
    }
    
    /**
     * 注册
     * @param Request $request
     */
    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6',
        ]);
        //保存当前用户注册信息
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        Auth::login($user);//已认证用户自动登录
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', $user);
    }
    
    /**
     * 编辑用户个人资料页面
     */
    public function edit(User $user) {
        $this->authorize('update', $user);
        return view("users.edit", compact('user'));
    }
    
    /**
     * 更新个人资料
     */
    public function update(User $user, Request $request) {
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'required|confirmed|min:6'
        ]);
        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);
        
        session()->flash('success', '个人资料更新成功！');
        
        return redirect()->route('users.show', $user->id);
    }
    
    public function destroy(User $user)
    {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }
    
    
}
