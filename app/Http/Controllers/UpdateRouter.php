<?php

namespace App\Http\Controllers;

use App\Models\Router as Router;
use Exception;
use Illuminate\Http\Request;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Query;

class UpdateRouter extends Controller
{
    public function index()
    {
        $routers= Router::orderBy('id', 'asc')->get();

        $this->checkUpdateStatus($routers);

        return view('update_router/list',[
            'routers'=>$routers
        ]);
    }

    public function update($id)
    {
        $router= Router::where('id',$id)->first();

        try {
            $config = new Config([
                'host' => $router->ip_address,
                'user' => $router->login,
                'pass' => $router->password,
                'port' => $router->port,
            ]);
            $client = new Client($config);
            $getUptimeQuery=new Query('/system/package/update/download');
            $data=$client->query($getUptimeQuery)->read();

            $getUptimeQuery=new Query('/system/reboot');
            $data=$client->query($getUptimeQuery)->read();

        } catch (Exception $e) {
            $routers[$key]['error'] = mb_convert_encoding($e->getMessage(), 'UTF-8');
            $routers[$key]['status'] = "Ошибка";
        }
    }

    public function checkUpdateStatus($routers)
    {
        foreach ($routers as $key=>$router) {
            try {
                $config = new Config([
                    'host' => $router->ip_address,
                    'user' => $router->login,
                    'pass' => $router->password,
                    'port' => $router->port,
                ]);
                $client = new Client($config);
                $getCheckUpdateQuery=new Query('/system/package/update/check-for-updates');
                $data=$client->query($getCheckUpdateQuery)->read();

                $parsedData=last($data);

                $routers[$key]['status'] = $parsedData['status'];
                $routers[$key]['channel'] = $parsedData['channel'];
                $routers[$key]['installed_version'] = $parsedData['installed-version'];

                if (!empty($parsedData['latest-version'])){
                    $routers[$key]['latest_version']=$parsedData['latest-version'];
                }else{
                    $routers[$key]['latest_version']='';
                }

            } catch (Exception $e) {
                $routers[$key]['error'] = mb_convert_encoding($e->getMessage(), 'UTF-8');
            }
        }

        return $routers;
    }
}
