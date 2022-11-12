<?php

namespace App\Http\Controllers;

use App\Models\Router as RouterModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Query;

class BackUpRouter extends Controller
{
    public function index()
    {
        $path=\App\Models\Path::where('id',1)->first()->path;

        $routers= RouterModel::all();

        foreach ($routers as $router){
            $config = new Config([
                'host' => $router->ip_address,
                'user' => $router->login,
                'pass' => $router->password,
                'port' => $router->port,
                'ssh_port' => $router->ssh_port,
            ]);
            $client = new Client($config);
            $userListQuery = new Query('/export');

            try{
                $data=$client->query($userListQuery)->read();
            }catch (Exception $e){

            }

            $disk = Storage::build([
                'driver' => 'local',
                'root' => $path,
            ]);

            $disk->put("/$router->name/".date('Y-m-d')."_$router->name.rsc",$data);
        }
    }
}
