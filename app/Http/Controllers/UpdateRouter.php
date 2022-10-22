<?php

namespace App\Http\Controllers;

use App\Models\Router as Router;
use Exception;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Query;

class UpdateRouter extends Controller
{
    public function index()
    {
        $routers= Router::orderBy('id', 'asc')->get();

        return view('update_router/list',[
            'routers'=>$routers
        ]);
    }

    public function check_update_stream()
    {
        return response()->stream(function () {

            $id = request()->route('id');
            $router= Router::where('id',$id)->first();

            $config = new Config([
                'host' => $router->ip_address,
                'user' => $router->login,
                'pass' => $router->password,
                'port' => $router->port,
            ]);
            $client = new Client($config);
            $getCheckUpdateQuery=new Query('/system/package/update/check-for-updates');
            $socket=$client->query($getCheckUpdateQuery)->getSocket();

            $status='open';

            while (true) {
                try{
                    $msg= $this->read($socket);
                }catch (Exception $e){
                    $msg= $e->getMessage();
                }

                $response=[];
                foreach ($msg as $item){
                    if ($item[0]!='!') {
                        list($key, $value) = explode("=", $item);
                        $response[$key] = $value;
                    }
                }

                if (isset($response['status'])) {
                    $message = $response['status'];
                }else{
                    $message = $msg[0];
                    $status='close';
                }

                if (!isset($response['latest-version'])){
                    $response['latest-version']='';
                }

                if (!isset($response['installed-version'])){
                    $response['installed-version']='';
                }

                if (!isset($response['status'])){
                    $response['status']='';
                }

                if (!isset($response['channel'])){
                    $response['channel']='';
                }

                echo "data: ".json_encode([
                        'router_status'=>$response['status'],
                        'installed_version'=>"{$response['installed-version']} - {$response['channel']}",
                        'latest_version'=>"{$response['latest-version']} - {$response['channel']}",
                        'status'=>$status
                    ])."\n\n";

                ob_flush();
                flush();

                if ($message=="!done"){
                    break;
                }

                usleep(50000);
            }
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'text/event-stream',
        ]);
    }

    function read($socket)
    {
        $_ = "";

        $RESPONSE = array();
        $receiveddone = false;
        while (true) {
            // Read the first byte of input which gives us some or all of the length
            // of the remaining reply.
            $BYTE = ord(fread($socket, 1));
            $LENGTH = 0;
            // If the first bit is set then we need to remove the first four bits, shift left 8
            // and then read another byte in.
            // We repeat this for the second and third bits.
            // If the fourth bit is set, we need to remove anything left in the first byte
            // and then read in yet another byte.
            if ($BYTE & 128) {
                if (($BYTE & 192) == 128) {
                    $LENGTH = (($BYTE & 63) << 8) + ord(fread($socket, 1));
                } else {
                    if (($BYTE & 224) == 192) {
                        $LENGTH = (($BYTE & 31) << 8) + ord(fread($socket, 1));
                        $LENGTH = ($LENGTH << 8) + ord(fread($socket, 1));
                    } else {
                        if (($BYTE & 240) == 224) {
                            $LENGTH = (($BYTE & 15) << 8) + ord(fread($socket, 1));
                            $LENGTH = ($LENGTH << 8) + ord(fread($socket, 1));
                            $LENGTH = ($LENGTH << 8) + ord(fread($socket, 1));
                        } else {
                            $LENGTH = ord(fread($socket, 1));
                            $LENGTH = ($LENGTH << 8) + ord(fread($socket, 1));
                            $LENGTH = ($LENGTH << 8) + ord(fread($socket, 1));
                            $LENGTH = ($LENGTH << 8) + ord(fread($socket, 1));
                        }
                    }
                }
            } else {
                $LENGTH = $BYTE;
            }
            // If we have got more characters to read, read them in.
            if ($LENGTH > 0) {
                $_ = "";
                $retlen = 0;
                while ($retlen < $LENGTH) {
                    $toread = $LENGTH - $retlen;
                    $_ .= fread($socket, $toread);
                    $retlen = strlen($_);
                }
                $RESPONSE[] = preg_replace('/^=/mi','',$_);
            }
            // If we get a !done, make a note of it.
            if ($_ == "!done" || preg_match('/section=\d+/mi',$_))
                $receiveddone = true;

            if ($receiveddone)
                break;
        }

        return $RESPONSE;
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
                'socket_blocking'=>false
            ]);
            $client = new Client($config);
            $getUptimeQuery=new Query('/system/package/update/download');
            $data=$client->query($getUptimeQuery)->read();

            $getUptimeQuery=new Query('/system/reboot');
            $data=$client->query($getUptimeQuery)->read();

        } catch (Exception $e) {
//            $routers[$key]['error'] = mb_convert_encoding($e->getMessage(), 'UTF-8');
//            $routers[$key]['status'] = "Ошибка";
        }
    }
}
