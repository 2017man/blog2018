<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $users = factory(User::class)->times(50)->make();
        User::insert($users->makeVisible(['password', 'remember_token'])->toArray());
        
        $user = User::find(1);
        $user->name = '满矅帆';
        $user->email = '3398284534@qq.com';
        $user->password = bcrypt('man5127666888');
        $user->is_admin = true;
        $user->activated = true;
        $user->save();
    }
}
