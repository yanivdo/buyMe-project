<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;



class ApiController extends Controller
{
    public function loginCheckController(Request $request)
    {
        $data = $request->input();
        $err = 0;
        $users = new Users();
        $dbResult = $users->getUser($data['email'], md5($data['password']));
        if (empty($dbResult)) {
            $err++;
            $result['finalMsg'] = "One or more of the details you entered are incorrect";
        } else {
            $dbResult = $dbResult[0];
            $data = $request->session()->put('login', 1);
            $result["answer"] = 1;
            $result["id"] = $dbResult->id;
            $this->id = $dbResult->id;
            $result['finalMsg'] = "Login Completed";
        }
        return json_encode($result);
    }

    public function registerCheckController(Request $request)
    {
        $data = $request->input();
        $submitCheck = $this->submitCheck($data);
        $err = $submitCheck['err'];
        $result = $submitCheck['result'];
        if ($err == 0) {
            $users = new Users();
            $mdPassword = md5($data['password']);
            $dbResult = $users->checkUser($data['email']);
            $result['finalMsg'] = "There is currently a user with this email";
            if (empty($dbResult)) {
                $newUser = $users->addUser($data['email'], $mdPassword, $data['name']);
                $result["id"] = $newUser;
                $result["answer"] = 1;
                $result['finalMsg'] = "Register Completed";
            }
        }
        return json_encode($result);
    }

    public function submitCheck($data)
    {
        $err = 0;
        $result = [
            "answer" => 0,
            "emailMsg" => "",
            "passwordMsg" => "",
            "finalMsg" => ""
        ];
        if ($data['email'] == "" || $this->regexEmail($data['email'])) {
            $result['emailMsg'] = 'Please enter valid Email';
            $err++;
        }
        if ($data['password'] == "" || $this->regexPassword($data['password'])) {
            $result['passwordMsg'] = 'Password must contain at least one letter 
            and one number and minimum of eight characters';
            $err++;
        }
        return ['result' => $result, 'err' => $err];
    }

    public function getSharedUsers(Request $request)
    {
        $data = $request->input();
        $data = $data['data'];
        $users = new Users();
        $result = $users->getUsersExcept($data['id'], $data['userId']);
        return json_encode($result);
    }

    public function regexEmail($email)
    {
        $response = 1;
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response = 0;
        }
        return $response;
    }

    public function regexPassword($password)
    {
        $response = 1;
        if (preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/s", $password)) {
            $response = 0;
        }
        return $response;
    }
}
