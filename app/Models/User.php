<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        return $this->statuses()
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
    
    
}
