<?php

namespace App\Commands;

use App\Lib\InvoiceDirectory;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ArchiveFoldersCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'archive-folders';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $invoiceDirectory = new InvoiceDirectory();

        if($invoiceDirectory->directoryExists()) {
            $invoiceDirectory->archiveDirectory();
            $this->info(sprintf("Successfully created zip file!"));
        } else {
            $this->error(sprintf("Didn't find %s directory!!", $invoiceDirectory->getInvoiceDirectoryName()));
        }

    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
