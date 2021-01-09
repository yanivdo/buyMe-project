<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tasks extends Model
{
    use HasFactory;

    public function getTasks($userId)
    {
        $data = DB::select("SELECT T.id, T.user_id, T.name, T.done, ST.shared_id, IF((ST.shared_id = ".$userId.") OR (ST.user_id = ".$userId."), 1, 0) AS `shared`
        FROM tasks T
        LEFT JOIN sharedtasks ST ON(T.id = ST.task_id)
        WHERE ST.shared_id = ".$userId."
        OR T.user_id = ".$userId."
        GROUP BY T.id;");
        return $data;
    }

    public function getSingleTasks($id)
    {
        $data = DB::select("select `id`, `user_id`,`name`, `done`
        FROM tasks 
        WHERE id = " . $id . ";");
        return $data;
    }

    public function checkTask($name)
    {
        $data = DB::select("select `id`, `user_id`,`name`, `done`
        FROM tasks 
        WHERE name = '" . $name . "';");
        return $data;
    }
    
    public function checkTaskByIdName($id, $name){
        $data = DB::select("select `id`, `user_id`,`name`, `done`
        FROM tasks 
        WHERE name = '" . $name . "'
        AND id != ".$id.";");
        return $data;
    }

    public function updateTask($taskId, $name){
        $data = DB::table('tasks')
        ->where('id', $taskId)
        ->update(['name' => $name]);
        return $data;
    }
    public function addTask($userId, $name, $shared = "")
    {
        $data = DB::table('tasks')->insertGetId(
            ['user_id' => $userId, 'name' => $name, 'done' => 0]
        );
        return $data;
    }

    public function updateDone($id, $checked){
        $data = DB::table('tasks')
        ->where('id', $id)
        ->update(['done' => $checked]);
        return $data;
    }

    public function deleteTask($id)
    {
        $data = DB::select("DELETE FROM tasks
        WHERE id = " . $id);
        return $data;
    }


}
