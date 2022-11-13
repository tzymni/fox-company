<?php

namespace App\Commands;

use App\Lib\InvoiceDirectory;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

/**
 * Prepare folders to store invoices.
 */
class PrepareFoldersCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'prepare-folders';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create directories for invoices for a new month.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $invoiceDirectory = new InvoiceDirectory();
        if ($invoiceDirectory->directoryExists()) {
            $this->warn(sprintf("Directory %s already exists!", $invoiceDirectory->getInvoiceDirectoryName()));
        } else {
            $this->info(sprintf("Didn't find %s directory. Started creating...", $invoiceDirectory->getInvoiceDirectoryName()));
            $invoiceDirectory->createDirectory();
            $this->info("Created all folders!");
        }

    }

    /**
     * Define the command's schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
