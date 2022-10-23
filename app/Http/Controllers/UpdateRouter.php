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
        $routers = Router::orderBy('id', 'asc')->get();

        return view('update_router/list', [
            'routers' => $routers
        ]);
    }

    public function check_update_stream()
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
            $client = new Client($config);
            $getCheckUpdateQuery = new Query('/system/package/update/check-for-updates');
            $socket = $client->query($getCheckUpdateQuery)->getSocket();

            $status = 'open';

            while (true) {
                $response=$this->getDataFromStreamAsArray($socket);

                if (isset($response['status'])) {
                    $message = $response['status'];
                } elseif (empty($response)) {
                    $status = 'close';
                }

                if (!isset($response['latest-version'])) {
                    $response['latest-version'] = '';
                }

                if (!isset($response['installed-version'])) {
                    $response['installed-version'] = '';
                }

                if (!isset($response['status'])) {
                    $response['status'] = '';
                }

                if (!isset($response['channel'])) {
                    $response['channel'] = '';
                }

                if ($response['latest-version'] == $response['installed-version'] || $response['latest-version'] == '') {
                    $checkbox = 'disable';
                } else {
                    $checkbox = 'enable';
                }

                $this->printPing();

                echo "data: " . json_encode([
                        'router_status' => $response['status'],
                        'installed_version' => "{$response['installed-version']} - {$response['channel']}",
                        'latest_version' => "{$response['latest-version']} - {$response['channel']}",
                        'checkbox' => $checkbox,
                        'status' => $status
                    ]) . "\n\n";

                ob_flush();
                flush();

                if ($message == "!done") {
                    break;
                }

                usleep(50000);
            }
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'text/event-stream',
        ]);
    }

    public function update_router_stream()
    {
        return response()->stream(function () {

            $id = request()->route('id');
            $router = Router::where('id', $id)->first();

            $config = new Config([
                'host' => $router->ip_address,
                'user' => $router->login,
                'pass' => $router->password,
                'port' => $router->port,
                'socket_blocking' => false
            ]);
            $client = new Client($config);
            $getDownloadQuery = new Query('/system/package/update/download');
            $socket = $client->query($getDownloadQuery)->getSocket();

            $status = 'open';
            while (true) {
                $response=$this->getDataFromStreamAsArray($socket);

                if (isset($response['status'])) {
                    $message = $response['status'];
                } elseif (empty($response)) {
                    $status = 'close';
                }

                if (!isset($response['latest-version'])) {
                    $response['latest-version'] = '';
                }

                if (!isset($response['installed-version'])) {
                    $response['installed-version'] = '';
                }

                if (!isset($response['status'])) {
                    $response['status'] = '';
                }

                if (!isset($response['channel'])) {
                    $response['channel'] = '';
                }

                $checkbox = 'disable';

                $this->printPing();

                echo "data: " . json_encode([
                        'router_status' => $response['status'],
                        'installed_version' => "{$response['installed-version']} - {$response['channel']}",
                        'latest_version' => "{$response['latest-version']} - {$response['channel']}",
                        'checkbox' => $checkbox,
                        'status' => $status
                    ]) . "\n\n";


                ob_flush();
                flush();

                if ($message == "!done") {
                    break;
                }

                usleep(50000);
            }

            if ($response['installed-version']!=$response['latest-version']) {

                $getRebootQuery = new Query('/system/reboot');
                $socket = $client->query($getRebootQuery)->getSocket();

                $responseReboot=$this->getDataFromStreamAsArray($socket);

                $responseReboot['status'] = 'rebooting';

                $this->printPing();

                echo "data: " . json_encode([
                        'router_status' => $responseReboot['status'],
                        'installed_version' => "{$response['installed-version']} - {$response['channel']}",
                        'latest_version' => "{$response['latest-version']} - {$response['channel']}",
                        'checkbox' => $checkbox,
                        'status' => 'close'
                    ]) . "\n\n";
            }else{
                $this->printPing();

                echo "data: " . json_encode([
                        'router_status' => $response['status'],
                        'installed_version' => "{$response['installed-version']} - {$response['channel']}",
                        'latest_version' => "{$response['latest-version']} - {$response['channel']}",
                        'checkbox' => $checkbox,
                        'status' => 'close'
                    ]) . "\n\n";
            }

        }, 200, [
            'Cache-Control' => 'no-cache',
            'Content-Type' => 'text/event-stream',
        ]);
    }

    function read($socket): array
    {
        $_ = "";

        $RESPONSE = array();
        $received_done = false;
        while (true) {
            // Read the first byte of input which gives us some or all of the length
            // of the remaining reply.
            $BYTE = ord(fread($socket, 1));
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
                $RESPONSE[] = preg_replace('/^=/mi', '', $_);
            }
            // If we get a !done, make a note of it.
            if ($_ == "!done" || preg_match('/section=\d+/mi', $_))
                $received_done = true;

            if ($received_done)
                break;
        }

        return $RESPONSE;
    }

    private function getDataFromStreamAsArray($socket): array
    {
        try {
            $msg = $this->read($socket);
        } catch (Exception $e) {
            $msg[0] = $e->getMessage();
        }

        $response = [];
        foreach ($msg as $item) {
            if ($item[0] != '!') {
                list($key, $value) = explode("=", $item);
                $response[$key] = $value;
            }
        }

        return $response;
    }

    private function printPing()
    {
        echo "event: ping\n",
            'data: {"time": "' . date("Y-m-d\TH:i:sO") . '"}', "\n\n";
    }
}
