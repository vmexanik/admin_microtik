<?php

namespace App\Http\Controllers;

use App\Models\Router as Router;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Query;

class UserRouter extends Controller
{
    public function index()
    {
        $routers = Router::orderBy('id', 'asc')->get();

        return view('user_router/list', [
            'routers' => $routers
        ]);
    }

    public function update_password()
    {
        $username=request('username');
        $password=request('password');
        $routers=request('routers');
        $return=[];

        foreach ($routers as $router){
            $routerInstance= Router::where('id', $router)->first();

            $config = new Config([
                'host' => $routerInstance->ip_address,
                'user' => $routerInstance->login,
                'pass' => $routerInstance->password,
                'port' => $routerInstance->port,
            ]);
            $client = new Client($config);
            $userListQuery = new Query('/user/print');
            $data=$client->query($userListQuery)->read();

            if (in_array($username,array_column($data,'name'))){
                foreach ($data as $datum) {
                    if ($username==$datum['name']) {
                        $updatePasswordQuery = (new Query('/user/set'))
                            ->equal('.id', $datum['.id'])
                            ->equal('password', $password);
                        $dataUpdate=$client->query($updatePasswordQuery)->read();

                        if ($routerInstance->login==$username){
                            $routerInstance->update(['id'=>$routerInstance->id,'password'=>$password]);
                        }

                        if (empty($dataUpdate)){
                            $return[$router]="Успех! $username:$password";
                        }
                    }
                }
            }else{
                $return[$router]="Неудача! $username не найден в списке пользователей роутера";
            }
        }

        return response($return);
    }
}
