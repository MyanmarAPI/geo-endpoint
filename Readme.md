## PHP Endpoint Bootstrap
==========================

This repo is intend to use as boilerplate for various endpoints developed in PHP.

### Installation

- Clone this repo
- Change your own remote
- Run composer install
- Place your codes

##### Application Environment

Create a file with name '.env' in your project root directory. And past the 
following code in it.

	APP_ENV=local
	APP_DEBUG=true

	APP_LOCALE=en
	APP_FALLBACK_LOCALE=en

	CACHE_DRIVER=memcached
	SESSION_DRIVER=memcached
	QUEUE_DRIVER=database

Running the following command will set X-API-KEY and X-API-SECRET for api request headers.

    php artisan api:key

***Should change value of APP_ENV to production and APP_DEBUG to false for production***

The value for **APP_KEY** should have 32 words which is combined with character 
and numeric.

##### Config

You can set key value pair for your application config in config/app.php.

##### For Using Api Support
[Api Support documentation](https://github.com/hexcores/api-support)

##### Importing data

To import data from csv to database, csv files need to be under ***storage/data*** directory. The directory should look like 
the following.

***To import data, models need to be created first.***

    Root
    |- Storage
        |- data
            |- 150709   * 9 July, 2015 *
                |- file1.csv
                |- file2.csv

And run artisan command

    // Usage
    php artisan iora:import [model] <--path="path/to/dir">

    // Example
    php artisan iora:import candidate --path="20150701"

csv filename needs to be the same with model name. When the above command is run, app will find modelname.csv
in the given directory. The above command will import data to Candidate model from candidate.csv

### Development

##### Model

Model files should be in app/Model directory. For more query function see 
[mongo lite documentation](https://github.com/hexcores/mongo-lite)

```php

	<?php namespace App\Model;
	
	use App\Model\AbstractModel;
	
	class User extends AbstractModel
	{
		public function getCollectionName()
		{
			return 'users';
		}
		
		public function create(array $data)
		{
			// do your stuff here
		}
	}

```

```php

	// app/http/routes.php
	$app->get('/get-users', function ()
	{
		dump((new \App\Model\User())->all());
	});

```

##### Route

All endpoints will be called from elecapi api router only. So your route should not be public. The follwoing example will be the right way.

```php

	// app/http/route.php
	
	$app->group(['middleware' => 'auth'], function () use ($app)
	{
    	
    	$app->get('/get-users', function ()
    	{
    		dump((new \App\Model\User())->all());
    	});
    	
        // all the rest of your route should be in this scope.

	});

```

## Further Documentation

- [lumen](http://lumen.laravel.com/docs)
- [mongolite](https://github.com/hexcores/mongo-lite)
- [support](https://github.com/hexcores/api-support)
- [fractal](http://fractal.thephpleague.com/)
- [csv](http://csv.thephpleague.com/)

***See [lumen documenation](http://lumen.laravel.com/docs) for further more 
documentation***

### Rules

##### Development

Must follow [psr](http://www.php-fig.org/) standard to develop endpoints.
Must follow lumen coding standard

### Technology

- [Lumne](http://lumen.laravel.com/) <Micro Framework from Larave>
- [Fractal](http://fractal.thephpleague.com/) <Composer package for REST API>
- [Mongo lite](https://github.com/hexcores/mongo-lite) <Composer package for mongodb>
