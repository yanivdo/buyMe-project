<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class SessionController extends Controller
{
    public function storeSession(Request $request){
        $request->session()->put('yaniv', 1);
        $value = $request->session()->all();
        print_r($value);
        exit;
    }

    public function getSession(Request $request){
        $value = $request->session()->all();

        print_r($value);
        die();
    }
}
