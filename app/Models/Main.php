<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Query;

class Main extends Model
{
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function checkStatus($routers)
    {
        foreach ($routers as $key=>$router) {
            $errorMsg = '';

            try {
                $config = new Config([
                    'host' => $router->ip_address,
                    'user' => $router->login,
                    'pass' => $router->password,
                    'port' => $router->port,
                ]);
                $client = new Client($config);
                $getUptimeQuery=new Query('/system/resource/print');
                $data=$client->query($getUptimeQuery)->read();
                $routers[$key]['status'] = "В работе";
                $routers[$key]['uptime'] = $data[0]['uptime'];
                $routers[$key]['os_version'] = $data[0]['version'];
            } catch (Exception $e) {
                $routers[$key]['error'] = mb_convert_encoding($e->getMessage(), 'UTF-8');
                $routers[$key]['status'] = "Ошибка";
            }

            return $routers;
        }
    }
}
