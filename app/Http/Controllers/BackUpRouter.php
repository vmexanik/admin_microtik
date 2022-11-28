<?php

namespace App\Http\Controllers;

use App\Models\Router as RouterModel;
use Carbon\Carbon;
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

        $disk = Storage::build([
            'driver' => 'local',
            'root' => $path,
        ]);

        foreach ($routers as $router){
            $time = Carbon::now();
            $timeUpdate=$time->toDateTimeString();

            try{
                $config = new Config([
                    'host' => $router->ip_address,
                    'user' => $router->login,
                    'pass' => $router->password,
                    'port' => $router->port,
                    'ssh_port' => $router->ssh_port,
                ]);

                $client = new Client($config);
                $userListQuery = new Query('/export');
                $data=$client->query($userListQuery)->read();
            }catch (Exception $e){
                $dataForUpdate['last_backup']=$timeUpdate." - ".$e->getMessage();
                $router->update($dataForUpdate);
                continue;
            }

            if ($disk->put("/$router->name/".date('Y-m-d')."_$router->name.rsc",$data)){
                $time = Carbon::now();
                $dataForUpdate['last_backup']=$timeUpdate;
                $router->update($dataForUpdate);
            }else{
                $dataForUpdate['last_backup']=$timeUpdate.' - Ошибка сохранения бекапа, проверь права и путь сохранения!';
                $router->update($dataForUpdate);
            }
        }
    }
}
