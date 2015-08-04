## Geo Endpoint
==========================
This repo is base on [PHP Endpoint Bootstrap](https://github.com/MyanmarAPI/php-endpoint-bootstrap)

### Installation

- Clone this repo
- run composer install

##### Applicaiont Environment

Create a file with name '.env' in your project root directory.

### Avaliable Api 

##### For Now District Location Available
	
For District of Myanmar

- Optional Parameters

	name and id

Example -
	
	geo/v1/district?name=:name
	geo/v1/district?id=:id

For District by location

	geo/v1/district/find?long=:longitude&lat=:latitude

District Location result have paginate.Default is 15. If u want to change limit u can set 

	geo/v1/district?per_page=3

and page too.

	geo/v1/district?page=4

### Technology

- [Lumne](http://lumen.laravel.com/) <Micro Framework from Larave>
- [Fractal](http://fractal.thephpleague.com/) <Composer package for REST API>
- [Monog lite](https://github.com/hexcores/mongo-lite) <Composer package for mongodb>
- [Api Support](https://github.com/hexcores/api-support)

### LICENSE

### DOC

[More Details For Geo](http://myanmarapi.github.io/endpoints/geolocation.html)

##### GNU Lesser General Public License v3 (LGPL-3)