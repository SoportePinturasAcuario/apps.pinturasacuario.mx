<?php

namespace Apps\Providers;

use Illuminate\Support\ServiceProvider;

class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        \Storage::extend('gdrive', function($app, $config){
            $client = new \Google_Client;
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->refreshToken('1//04QvftK6Jxq1NCgYIARAAGAQSNwF-L9Ir_4qJYqJRJXDtws5tjfKsVWujbTwG5E7AWVc5JqxpwWp178ZvTAKIGxiHvCRnYD30ZCs');
            $service = new \Google_Service_Drive($client);
            $adapter = new \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter($service, $config['folderId']);

            // return new \Filesystem($adapter);
            return new \League\Flysystem\Filesystem($adapter);
        });     
    }
}
