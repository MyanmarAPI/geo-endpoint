<?php
/**
 * This file is part of php-endpoint-bootstrap project
 * 
 * @package App\Console\Commands
 * @author Yan Naing <yannaing@hexcores.com>
 * Date: 6/30/15
 * Time: 1:16 PM
 */

namespace App\Console\Commands;

use App\Iora\Reader;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ImportCommand extends Command{

    protected $name = 'iora:import';

    protected $description = 'Import all data from csv files';

    protected $filesystem;

    /**
     * Constructor method for ImportCommand class
     */
    public function __construct()
    {
        parent::__construct();

        $this->filesystem = app('files');
    }

    /**
     * Fire the command
     */
    public function fire()
    {
        $this->import();
    }

    protected function import()
    {
        $model = $this->input->getArgument('model');

        $path = $this->input->getOption('path');

        $path = (is_null($path)) ? storage_path('data') : storage_path('data/' . $path);

        $file = $path . '/' . $model . '.csv';

        if (! $this->filesystem->exists($file))
        {
            return $this->line('[ERROR !] File not found - ' . $file);
        }

        $this->info('Importing data to ' . $model);

        $reader = new Reader($file);

        $reader->model($model)->import();

        $this->info('[SUCCESS] Imported ' . $reader->getRows() . ' rows');
    }

    /**
     * Get command arguments
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['model', InputArgument::REQUIRED, 'Model name to set data']
        ];
    }

    /**
     * Get command options
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['path', null, InputOption::VALUE_OPTIONAL, '--path="path/to/dir" Directory which contain csv data files']
        ];
    }

}