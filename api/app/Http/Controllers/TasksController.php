<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tasks;
use App\Models\shareTasks;


class TasksController extends Controller
{
    public function getTasks(Request $request)
    {
        $data = $request->input();
        $db = new Tasks();
        $id = $data['id'];
        $done = 0;
        $result = $db->getTasks($id);

        foreach ($result as $key => $singleResult) {
            if ($singleResult->done) {
                $done++;
            }
            if($singleResult->shared_id != null){
                $result[$key]->shared = 1;
            }
        }
        return json_encode(['tasks' => $result, 'doneTasks' => $done]);
    }

    public function checkDone(Request $request)
    {
        $data = $request->input();
        $data = $data['data'];
        $checked = 0;
        $result = [];
        if ($data['checked']) {
            $checked = 1;
        }
        $data['checked'] = $checked;
        $db = new Tasks();
        $db->updateDone($data['id'], $data['checked']);
        $result["answer"] = 1;
        $result['data'] = $db->getSingleTasks($data['id']);
        return json_encode($result);
    }
    public function addTask(Request $request)
    {
        $data = $request->input();
        $data = $data['data'];
        $result = [];
        $db = new Tasks();
        $checkTask = $db->checkTask($data['name']);
        if (!empty($checkTask)) {
            $result["finalMsg"] = "There is a task with that name. Please change the name";
            $result["answer"] = 0;
        } else {
            $newTask = $db->addTask($data['id'], $data['name']);
            $result['task'] = $db->getSingleTasks($newTask);
            $result["answer"] = 1;
            $result["finalMsg"] = "";
        }
        return json_encode($result);
    }

    public function deleteTask(Request $request)
    {
        $data = $request->input();
        $data = $data['data'];
        $db = new Tasks();
        $db->deleteTask($data['taskId']);
        $newTasks = $db->getTasks($data['userId']);
        $shareDB = new shareTasks();
        $shareDB->deleteShare($data['taskId']);
        return json_encode($newTasks);
    }

public function editTask(Request $request){
    $data = $request->input();
    $data = $data['data'];
    $db = new Tasks();
    $checkData = $db->checkTaskByIdName($data['taskId'], $data['name']);
    if(empty($checkData)){
        $db->updateTask($data['taskId'], $data['name']);
        $result["answer"] = 1;
    }else{
        $result["answer"] = 0;
        $result['finalMsg'] = "There is a task with that name.";
    }
    $result['task'] = $db->getSingleTasks($data['taskId']);
    return json_encode($result);
}

    public function changeShare(Request $request)
    {
        $data = $request->input();
        $data = $data['data'];
        $db = new Tasks();
        $shareDB = new shareTasks();
        if ($data['checked']) {
            $check = $shareDB->checkShare($data['userId'], $data['shareId'], $data['taskId']);
                if (empty($check)) {
                    $shareDB->addShare($data['userId'], $data['shareId'], $data['taskId']);
                    $result['answer'] = 1;
            }else{
                $result['answer'] = 0;
            }
        }else{
            $shareDB->deleteSpecificShare($data['userId'], $data['shareId'], $data['taskId']);
            $result['answer'] = 1;
        }
        $result = $db->getTasks($data['userId']);
        return json_encode($result);
    }
}
