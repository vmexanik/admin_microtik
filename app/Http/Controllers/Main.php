<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Main extends Controller
{
    public function index()
    {
        $routers=\App\Models\Router::orderBy('id', 'asc')->get();

        $mainModel=new \App\Models\Main();
        $mainModel->checkStatus($routers);

        return view('welcome',[
            'routers'=>$routers
        ]);
    }
}
