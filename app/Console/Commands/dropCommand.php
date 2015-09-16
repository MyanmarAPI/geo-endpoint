<?php
/**
 * This file is part of php-endpoint-bootstrap project
 * 
 * @package App\Console\Commands
 * @author Nyan Lynn Htut <naynlynnhtut@hexcores.com>
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class DropCommand extends Command
{

    protected $name = 'iora:drop';

    protected $description = 'Drop the collection from database';

    /**
     * Fire the command
     */
    public function fire()
    {
        $collection = $this->input->getArgument('name');

        $this->info('Drop the collection ' . $collection);

         mongo_lite($collection)->collection()->drop();
    }
    
    /**
     * Get command arguments
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Collection name to drop']
        ];
    }

}