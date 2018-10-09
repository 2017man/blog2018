<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Status;
use App\Models\User;
use Auth;

class StatusesController extends Controller {
    
    public function __construct() {
        
        $this->middleware('auth',
            ['except' => ['store', 'destroy ', 'update', 'edit','create'],
            ]);
    }
    
    //发表界面
    public function create(User $user) {
        return view('statuses.create');
    }
    
    //创建微博
    public function store(Request $request) {
        
        //内容非空
        $this->validate($request, ['content' => 'required']);
        //获取当前用户示例，创建微博
        Auth::user()->statuses()->create(['content' => $request['content']]);
        $user = Auth::user();
        session()->flash('success', '微博发布成功！');
        return redirect()->back();
//        return redirect('/');
        
    }
    
    //微博删除
    public function destroy(Status $status) {
        $this->authorize('destroy', $status);
        $status->delete();
        session()->flash('success', '微博已被成功删除！');
        return redirect()->back();
    }
}
