<?php

namespace App\Http\Controllers;

use App\Models\Router as RouterModel;
use Carbon\Carbon;
use ErrorException;
use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Query;

class BackUpRouter extends Controller
{
    private Filesystem $storage;

    public function index()
    {
        $path=\App\Models\Path::where('id',1)->first();

        $routers= RouterModel::all();

        foreach ($routers as $router){
            $dataForUpdate=[];
            $time = Carbon::now();
            $timeUpdate=$time->format('Y-m-d');

            try{
                $config = new Config([
                    'host' => $router->ip_address,
                    'user' => $router->login,
                    'pass' => $router->password,
                    'port' => $router->port,
                    'ssh_port' => $router->ssh_port,
                ]);

                $this->initStorage($path->host, $path->user, $path->password, $path->path, $router->name);

                $backupFilename="$timeUpdate-$router->name.rsc";
                $binaryBackupFilename="$timeUpdate-$router->name.backup";

                $client = new Client($config);
                $exportQuery = new Query('/export');
                $dataExport=$client->query($exportQuery)->read();

                $this->putFileIntoDisk($router->name, $backupFilename, $dataExport);

                $systemBackupQuery = new Query('/system/backup/save');
                $systemBackupQuery->equal('name', $binaryBackupFilename);
                $client->query($systemBackupQuery)->read();

                $queryFetchBackup =new Query('/tool/fetch');

                $queryFetchBackup->equal('address', $path->host)
                    ->equal('mode', 'ftp')
                    ->equal('user', $path->user)
                    ->equal('password', $path->password)
                    ->equal('src-path', $binaryBackupFilename)
                    ->equal('dst-path', "/test/$router->name/$binaryBackupFilename")
                    ->equal('keep-result', "yes")
                    ->equal('upload', "yes");

                $responseFetchBackup=$client->query($queryFetchBackup)->read();

                if ($responseFetchBackup[1]['status']!='finished' || $responseFetchBackup[1]['uploaded']=0){
                    throw new ErrorException('cannot upload binary backup by ftp');
                }

                $systemBackupQueryDelete = new Query('/file/remove');
                $systemBackupQueryDelete->equal('numbers',$binaryBackupFilename);
                $responseDelete=$client->query($systemBackupQueryDelete)->read();

            }catch (Exception $e){
                $dataForUpdate['last_backup']=$timeUpdate." - ".$e->getMessage();
                $router->update($dataForUpdate);
                continue;
            }

            $dataForUpdate['last_backup']=$timeUpdate;
            $dataForUpdate['last_success_backup']=$timeUpdate;
            $router->update($dataForUpdate);
        }
    }

    private function putFileIntoDisk($routerName, $backupFilename,  $data): void
    {
        $this->storage->put("/$routerName/$backupFilename", $data);
    }

    private function initStorage($host, $user, $password, $rootDirectory, $routerName)
    {
        $disk = Storage::build([
            'driver' => 'ftp',
            'host'     => $host,
            'username' => $user,
            'password' => $password,
            'root'=>$rootDirectory
        ]);

        $disk->createDirectory($routerName);

        $this->storage=$disk;
    }
}
