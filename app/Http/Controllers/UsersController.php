<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Mail;

class UsersController extends Controller {
    
    public function __construct() {
        //只允许登陆的用户相关的操作
        $this->middleware('auth',
            ['except' => ['show', 'create', 'store', 'index', 'confirmEmail'],
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
        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');
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
    
    public function destroy(User $user) {
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }
    
    /**
     * 邮件发送
     */
    protected function sendEmailConfirmationTo($user) {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = '3398284534@qq.com';
        $name = '满曜帆';
        $to = $user->email;
        $subject = "感谢注册 blog 2018 应用！请确认你的邮箱。";
        
        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }
    
    public function confirmEmail($token) {
        $user = User::where('activation_token', $token)->firstOrFail();
        
        $user->activated = true;
        $user->activation_token = null;
        $user->save();
        
        Auth::login($user);
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }
    
}
