<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Users extends Model
{
    use HasFactory;

    public function getUser($email, $password){
        $data = DB::select("select `id`, `email`,`password` 
        FROM users 
        WHERE email = '" . $email . "' 
        AND password = '" . $password. "';");
        return $data;
    }

    public function getUsersExcept($taskId, $userId){
        $data = DB::select("SELECT U.`id`, U.`name` FROM users U
        WHERE U.id NOT IN(SELECT T.user_id FROM tasks T WHERE T.id = ".$taskId.")
        AND U.id NOT IN(SELECT ST.shared_id FROM sharedtasks ST WHERE ST.task_id = ".$taskId.");");
        return $data;
    }

    public function checkUser($email){
        $data = DB::select("select `id`, `email`,`password` 
        FROM users 
        WHERE email = '" . $email . "';");
        return $data;
    }

    public function addUser($email, $password, $name){
        $data = DB::table('users')->insertGetId(['email' => $email, 'password' => $password, 'name' => $name]);
        return $data;
    }
}
