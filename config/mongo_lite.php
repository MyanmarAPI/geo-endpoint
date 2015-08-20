<?php 

return [
    'host'      => env('DB_HOST'),
    'port'      => env('DB_PORT', '27017'),
    'database'  => env('DB_DATABASE'),
    'user'      => env('DB_USERNAME'),
    'password'  => env('DB_PASSWORD')
];
