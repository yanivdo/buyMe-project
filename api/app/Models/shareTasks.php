<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class shareTasks extends Model
{
    use HasFactory;

    public function deleteShare($taskId)
    {
        DB::table('sharedtasks')->where('task_id', $taskId)->delete();
    }

    public function checkShare($userId, $sharedId, $taskId){
        $data = DB::select("SELECT `id` FROM sharedtasks
        WHERE task_id = " . $taskId."
        AND user_id = ".$userId." 
        AND shared_id = ".$sharedId);
        return $data;
    }


    public function addShare($userId, $sharedId, $taskId){
        $data = DB::table('sharedtasks')->insertGetId(
            ['user_id' => $userId, 'shared_id' => $sharedId, 'task_id' => $taskId]
        );
        return $data;
    }

    public function deleteSpecificShare($userId, $sharedId, $taskId){
        $deleted = DB::delete('DELETE FROM sharedtasks 
        WHERE user_id = ?
        AND shared_id = ?
        AND task_id = ?',
        [$userId, $sharedId, $taskId]);
    }
}
