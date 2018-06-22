<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller {
    
    public function __construct() {
        //只允许为登陆的用户访问
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }
    
    //登录界面
    public function create() {
        return view('sessions.create');
    }
    
    //登录验证
    public function store(Request $request) {
        
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);
        //认证成功--显示个人信息
        if (Auth::attempt($credentials, $request->has('remember'))) {
            
            session()->flash('success', '欢迎回来！');
            return redirect()->intended(route('users.show', [Auth::user()]));
        } else {//认证失败-返回页面
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back();
        }
    }
    
    /**
     * 退出
     */
    public function destroy() {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }
    
}
