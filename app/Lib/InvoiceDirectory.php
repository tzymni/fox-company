<?php

namespace App\Lib;

use ZipArchive;

/**
 * Class to prepare invoice directories based on information from .env configuration file.
 */
class InvoiceDirectory
{

    /**
     * @var string
     */
    public string $directoryPath;

    /**
     * @var string
     */
    public string $invoiceDirectoryName;

    /**
     * @return string
     */
    public function getInvoiceDirectoryName(): string
    {
        return $this->invoiceDirectoryName;
    }

    /**
     * @return string
     */
    public function getDirectoryPath(): string
    {
        return $this->directoryPath;
    }

    /**
     *
     * @param string|null $invoiceDirectoryName By default, Y.m of the last month
     */
    public function __construct(string $invoiceDirectoryName = null)
    {
        $this->directoryPath = getenv('HOME') . '/' . env('INVOICE_DIRECTORY_PATH');
        if (empty($invoiceDirectoryName)) {
            $this->invoiceDirectoryName = gmdate("Y.m", strtotime("-1 month")) . 'test';
        } else {
            $this->invoiceDirectoryName = $invoiceDirectoryName;
        }
    }

    /**
     * Check if directory already exists.
     *
     * @return bool
     */
    public function directoryExists(): bool
    {
        return file_exists($this->directoryPath . $this->invoiceDirectoryName);
    }

    /**
     * Create directory with subdirectories.
     * Subdirectory names based on
     *
     * INVOICE_COSTS_DIRECTORY_NAME
     * INVOICE_CAR_COSTS_DIRECTORY_NAME
     * INVOICE_SELL_DIRECTORY_NAME
     * env configurations.
     *
     * @return void
     */
    public function createDirectory(): void
    {
        $directoryPath = $this->directoryPath;
        $invoiceDirectoryName = $this->invoiceDirectoryName;
        mkdir($directoryPath . $invoiceDirectoryName);
        mkdir($directoryPath . $invoiceDirectoryName . '/' . env('INVOICE_COSTS_DIRECTORY_NAME'));

        if (env('INVOICE_CAR_COSTS_DIRECTORY_NAME') !== null) {
            mkdir($directoryPath . $invoiceDirectoryName . '/' . env('INVOICE_COSTS_DIRECTORY_NAME') . '/' . env('INVOICE_CAR_COSTS_DIRECTORY_NAME'));
        }
        mkdir($directoryPath . $invoiceDirectoryName . '/' . env('INVOICE_SELL_DIRECTORY_NAME'));
    }

    public function archiveDirectory()
    {

        $directoryPath = $this->getDirectoryPath();
        $invoiceArchiveName = $this->getInvoiceDirectoryName() . '.zip';

        $fullArchivePath = $directoryPath . '/' . $invoiceArchiveName;
        $fullInvoiceDirectoryPath = $directoryPath . '/' . $this->getInvoiceDirectoryName();

        $zip = new ZipArchive();

        if ($zip->open($fullArchivePath, ZipArchive::CREATE) === TRUE) {

            // Store the path into the variable
            $dir = opendir($fullInvoiceDirectoryPath);

            while ($file = readdir($dir)) {
                if (is_file($fullInvoiceDirectoryPath . $file)) {
                    $zip->addFile($fullInvoiceDirectoryPath . $file, $file);
                }
            }
            $zip->close();
        }
    }


}
