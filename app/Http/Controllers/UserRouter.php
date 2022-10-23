<?php

namespace App\Http\Controllers;

use App\Models\Router as Router;
use Illuminate\Http\Request;

class UserRouter extends Controller
{
    public function index()
    {
        $routers = Router::orderBy('id', 'asc')->get();

        return view('user_router/list', [
            'routers' => $routers
        ]);
    }
}
