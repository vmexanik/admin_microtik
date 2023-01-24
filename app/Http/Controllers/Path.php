<?php

namespace App\Http\Controllers;

use App\Models\Path as PathModel;
use Illuminate\Http\Request;

class Path extends Controller
{
    public function store(Request $request, PathModel $pathModel)
    {
        $path= $pathModel::where('id', 1)->first();
        if (empty($path)){
            $pathModel->id=1;
            $pathModel->path='/';
            $pathModel->save();
        }

        $request->validate([
            'path' => 'required',
            'user' => 'required',
            'password' => 'required',
            'host' => 'required'
        ]);

        $dataForUpdate['path']=$request->path;
        $dataForUpdate['user']=$request->user;
        $dataForUpdate['password']=$request->password;
        $dataForUpdate['host']=$request->host;
        $dataForUpdate['id']=1;

        $pathModel->exists=true;
        $pathModel->update($dataForUpdate);

        return redirect('/router')->with('path_status','Сохранение успешно!');
    }
}
