<?php

namespace App\Http\Controllers;

use App\Models\Router as RouterModel;
use Carbon\Carbon;
use Exception;
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
            $time = Carbon::now();
            $timeUpdate=$time->toDateTimeString('minute');

            try{
                $config = new Config([
                    'host' => $router->ip_address,
                    'user' => $router->login,
                    'pass' => $router->password,
                    'port' => $router->port,
                    'ssh_port' => $router->ssh_port,
                ]);

                $client = new Client($config);
                $exportQuery = new Query('/export');
                $dataExport=$client->query($exportQuery)->read();
                $backupFilename="$router->name.rsc";

                $systemBackupQuery = new Query('/system/backup/save');
                $systemBackupQuery->equal('name', 'system_backup_'.$timeUpdate);
                $client->query($systemBackupQuery);

                $binaryBackupFilename='system_backup_'.$timeUpdate;
                $binaryBackupExtension='backup';

                $ftp=Storage::build([
                    'driver' => 'ftp',
                    'host'     => $router->ip_address,
                    'username' => $router->login,
                    'password' => $router->password,
                    'port'     => $router->ftp_port,
                    'timeout'  => 30,
                ]);

                $file =$ftp->download("$backupFilename.$binaryBackupExtension");

            }catch (Exception $e){
                $dataForUpdate['last_backup']=$timeUpdate." - ".$e->getMessage();
                $router->update($dataForUpdate);
                continue;
            }

            if ($this->putFileIntoDisk($path, $router->name,$backupFilename,$dataExport) ||
                $this->putFileIntoDisk($path, $router->name,"$binaryBackupFilename.$binaryBackupExtension",$file)){
                $dataForUpdate['last_backup']=$timeUpdate;
                $router->update($dataForUpdate);

                $systemBackupQuery = new Query('/file/remove');
                $systemBackupQuery->equal('name', "$binaryBackupFilename.$binaryBackupExtension");
                $client->query($systemBackupQuery);
            }else{
                $dataForUpdate['last_backup']=$timeUpdate.' - Ошибка сохранения бекапа, проверь права и путь сохранения!';
                $router->update($dataForUpdate);
            }
        }
    }

    private function putFileIntoDisk($path,$routerName, $backupFilename,  $data): bool
    {
        $disk = Storage::build([
            'driver' => 'local',
            'root' => $path,
        ]);

       return  $disk->put("/$routerName/".date('Y-m-d')."_$backupFilename",$data);
    }
}
