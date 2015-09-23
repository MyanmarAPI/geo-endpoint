<?php namespace App\Console\Commands;

/**
 * Mongo DB Import (json) Command
 * Example :
 * <code>
 * 	
 * 	php artisan json:import collectionname path/to/dir/filename.json
 * </code>
 *
 * @package App\Console\Commands
 * @author  Hexcores Web and Mobile Studio <support@hexcores.com>
 * @link http://hexcores.com
 */

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class JsonImportCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'json:import';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'MongoDB import command for Application';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $this->import();
	}

	/**
	 * Run import command with process
	 * @return void
	 */
	protected function import()
	{
		$collection = $this->input->getArgument('model');

		$path = $this->input->getArgument('path');

		if ( ! app('files')->exists($path)) {
			throw new \InvalidArgumentException("$path is does not exit");
		}
		$file=file_get_contents($path);
		$json=json_decode($file);
		$features=json_encode($json->features);
		$tempfile=dirname($path).'/'.'mongo-'.basename($path);
		file_put_contents($tempfile, $features);
		
		$host = $this->getMongoHost();

		$db = $this->getDatabaseName();

		$this->line('MongoDB Importing to ' . $db);
		
		mongo_lite($collection)->collection()->createIndex(array('geometry' => '2dsphere'));
		$command = 'mongoimport -h '.$host.' -d'.$db;
		
		$process = new Process($command . ' -c ' . $collection . ' --file '. $tempfile . ' --jsonArray' );

		$process->run();

		if ( ! $process->isSuccessful()) 
		{
			unlink($tempfile);
			$this->error('Error collection - ' . $collection);

			throw new \RuntimeException($process->getErrorOutput());
		}
		unlink($tempfile);
		$this->info('Import collection - ' . $collection);

		$this->info('Finish mongo import to database '. $db);
	}

	/**
	 * Get mongo host and port
	 * @return string
	 */
	protected function getMongoHost()
	{
		$config = config('mongo_lite');

		$host = $config['host'];
		$port = $config['port'];

		return $host . ':' . $port;
	}

	/**
	 * Get database name.
	 * 
	 * @return string
	 */
	protected function getDatabaseName()
	{
		return config('mongo_lite')['database'];
	}

    /**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			['model', InputArgument::REQUIRED, 'Model name to set data'],
			['path', InputArgument::REQUIRED, '--path="path/to/dir/file.json"  Directory which contain json data file to import']
		);
	}

}
