<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model {
    //一条微博为某个用户所有
    protected $fillable = ['content'];
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
}
