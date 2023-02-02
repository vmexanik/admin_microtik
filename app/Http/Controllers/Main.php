<?php

namespace App\Http\Controllers;

use App\Models\Router as Router;
use Exception;
use Illuminate\Http\Request;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Query;

class Main extends Controller
{
    public function index()
    {
        $routers = Router::orderBy('id', 'asc')->get();

        return view('welcome', [
            'routers' => $routers
        ]);
    }

    public function check_router_status_stream()
    {
        return response()->stream(function () {

            $id = request()->route('id');
            $router = Router::where('id', $id)->first();

            $config = new Config([
                'host' => $router->ip_address,
                'user' => $router->login,
                'pass' => $router->password,
                'port' => $router->port,
            ]);

            $response=[];

            try {
                $client = new Client($config);
                $getUptimeQuery = new Query('/system/resource/print');
                $routerStatus="В работе";
                $error='';
                $response=$client->query($getUptimeQuery)->read()[0];
            } catch (Exception $e) {
                $error= mb_convert_encoding($e->getMessage(), 'UTF-8');
                $routerStatus = "Ошибка";
            }

            $status = 'close';

            if (!isset($response['uptime'])) {
                $response['uptime'] = '';
            }

            if (!isset($response['version'])) {
                $response['version'] = '';
            }

            if (!isset($response['router_status'])) {
                $response['router_status'] =$routerStatus;
            }

            if (!isset($response['error'])) {
                $response['error'] =$error;
            }

            $this->printPing();

            echo "data: " . json_encode([
                    'uptime' => $response['uptime'],
                    'os_version' => $response['version'],
                    'router_status' => $response['router_status'],
                    'error'=>$response['error'],
                    'status' => $status
                ]) . "\n\n";

            ob_flush();
            flush();
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'text/event-stream',
        ]);
    }

    private function printPing()
    {
        echo "event: ping\n",
            'data: {"time": "' . date("Y-m-d\TH:i:sO") . '"}', "\n\n";
    }
}
