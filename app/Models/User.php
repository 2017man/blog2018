<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;

use App\Notifications\ResetPassword;

class User extends Authenticatable {
    use Notifiable;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public static function boot() {
        parent::boot();
        static::creating(function ($user) {
            $user->activation_token = str_random(30);
        });
    }
    
    //一个用户对应多条微博
    public function statuses() {
        return $this->hasMany(Status::class);
    }
    
    //加载微博
    public function feed()
    {
        $user_ids = Auth::user()->followings->pluck('id')->toArray();
        array_push($user_ids, Auth::user()->id);
        return Status::whereIn('user_id', $user_ids)
            ->with('user')
            ->orderBy('created_at', 'desc');
    }
    
    /**
     * 通用用户头像
     * @param string $size
     * @return string
     */
    public function gravatar($size = '100') {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }
    
    /**
     * 通过邮发送密码重置消息
     * @param string $token
     */
    public function sendPasswordResetNotification($token) {
        $this->notify(new ResetPassword($token));
    }
    
    /**
     * 获取粉丝关系列表
     * @return mixed
     */
    public function followers() {
        return $this->belongsToMany(User::Class, 'followers', 'user_id', 'follower_id');
    }
    
    /**
     * 获取用户关注人列表
     * @return mixed
     */
    public function followings() {
        return $this->belongsToMany(User::Class, 'followers', 'follower_id', 'user_id');
    }
    
    //关注
    public function follow($user_ids) {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids, false);
    }
    
    //取消关注
    public function unfollow($user_ids) {
        if (!is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }
    
    /**
     * 判断当前登录的用户 A 是否关注了用户 B
     * @param $user_id
     * @return mixed
     */
    public function isFollowing($user_id) {
        return $this->followings->contains($user_id);
    }
    
    
    
    
}
