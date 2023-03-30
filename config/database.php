<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'claims' => [
            'driver' => 'sqlite',
            'database' => database_path('SQLite/claims.sqlite'),
            'prefix' => '',
            'foreign_key_constraints' => true,
        ],   

        'scanning' => [
            'driver' => 'sqlite',
            'database' => database_path('SQLite/scanning.sqlite'),
            'prefix' => '',
            'foreign_key_constraints' => true,
        ],           

        'checklists' => [
            'driver' => 'sqlite',
            'database' => database_path('SQLite/checklists.sqlite'),
            'prefix' => '',
            'foreign_key_constraints' => true,
        ],   

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],

        'nom' => [
            'driver' => 'mysql',
            'host' => env('RH_HOST', '127.0.0.1'),
            'port' => env('RH_PORT', '3306'),
            'database' => env('RH_DATABASE', 'forge'),
            'username' => env('RH_USERNAME', 'forge'),
            'password' => env('RH_PASSWORD', ''),
            'unix_socket' => env('RH_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],  

        'rh' => [
            'driver' => 'mysql',
            'host' => env('RH_HOST', '127.0.0.1'),
            'port' => env('RH_PORT', '3306'),
            'database' => env('RH_DATABASE', 'forge'),
            'username' => env('RH_USERNAME', 'forge'),
            'password' => env('RH_PASSWORD', ''),
            'unix_socket' => env('RH_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],  

        'ti' => [
            'driver' => 'mysql',
            'host' => env('TI_HOST', '127.0.0.1'),
            'port' => env('TI_PORT', '3306'),
            'database' => env('TI_DATABASE', 'forge'),
            'username' => env('TI_USERNAME', 'forge'),
            'password' => env('TI_PASSWORD', ''),
            'unix_socket' => env('TI_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],

        'store' => [
            'driver' => 'mysql',
            'host' => env('STORE_HOST', '127.0.0.1'),
            'port' => env('STORE_PORT', '3306'),
            'database' => env('STORE_DATABASE', 'forge'),
            'username' => env('STORE_USERNAME', 'forge'),
            'password' => env('STORE_PASSWORD', ''),
            'unix_socket' => env('STORE_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ], 

        'inventory' => [
            'driver' => 'mysql',
            'host' => env('INV_HOST', '127.0.0.1'),
            'port' => env('INV_PORT', '3306'),
            'database' => env('INV_DATABASE', 'forge'),
            'username' => env('INV_USERNAME', 'forge'),
            'password' => env('INV_PASSWORD', ''),
            'unix_socket' => env('INV_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],  

        'sca' => [
            'driver' => 'mysql',
            'host' => env('SCA_HOST', '127.0.0.1'),
            'port' => env('SCA_PORT', '3306'),
            'database' => env('SCA_DATABASE', 'forge'),
            'username' => env('SCA_USERNAME', 'forge'),
            'password' => env('SCA_PASSWORD', ''),
            'unix_socket' => env('SCA_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],  

        'bda' => [
            'driver' => 'mysql',
            'host' => env('BDA_HOST', '127.0.0.1'),
            'port' => env('BDA_PORT', '3306'),
            'database' => env('BDA_DATABASE', 'forge'),
            'username' => env('BDA_USERNAME', 'forge'),
            'password' => env('BDA_PASSWORD', ''),
            'unix_socket' => env('BDA_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],          

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => 'predis',

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DB', 0),
        ],

        'cache' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_CACHE_DB', 1),
        ],

    ],

];
