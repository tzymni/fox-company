<?php

namespace App\Commands;

use App\Lib\ZipArchiveExtend;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class BackupToRemoteServerCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'backup-to-remote-server';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Archive and backup main documentation directory to the remote server.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mainDirectoryPath = getenv('HOME') . '/' . getenv('MAIN_DOCUMENTATION_DIRECTORY');
        $archivedFileFullPath = $mainDirectoryPath . '/../archive-documents-' . gmdate('Y-m-d') . '.zip';
        $archive = new ZipArchiveExtend();
        $success = $archive->zip($mainDirectoryPath, $mainDirectoryPath . '/../archive-documents-' . gmdate('Y-m-d') . '.zip');
        if ($success) {
            $this->info('Successfully archived main directory. Starting backup it to the remote server...');
            exec(sprintf('sshpass -p "%s" scp -P %s %s %s@%s:~/%s', getenv('SERVER_PASSWORD'), getenv('SERVER_PORT'), $archivedFileFullPath, getenv('SERVER_USER'), getenv('SERVER_ADDRESS'), getenv('SERVER_BACKUP_DIRECTORY')));
            $this->info('Success!');
        } else {
            $this->error('Something goes wrong :<');
        }

    }

    /**
     * Define the command's schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
