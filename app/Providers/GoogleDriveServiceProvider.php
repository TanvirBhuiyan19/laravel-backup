<?php

namespace App\Providers;

use Google;
use Storage;
use League\Flysystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;

class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Storage::extend('google', function ($app, $config) {
        //     $client = new Google_Client();
        //     $client->setClientId($config['clientId']);
        //     $client->setClientSecret($config['clientSecret']);
        //     $client->refreshToken($config['refreshToken']);
        //     $service = new \Google_Service_Drive($client);
        //     $adapter = new GoogleDriveAdapter($service, $config['folderId']);
        //     return new Filesystem($adapter);
        // });


        \Storage::extend('google', function($app, $config) {
            $options = [];

            if (!empty($config['teamDriveId'] ?? null)) {
                $options['teamDriveId'] = $config['teamDriveId'];
            }

            $client = new \Google\Client();
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->refreshToken($config['refreshToken']);

            $service = new \Google\Service\Drive($client);
            $adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service, $config['folder'] ?? '/', $options);
            $driver = new \League\Flysystem\Filesystem($adapter);

            return new \Illuminate\Filesystem\FilesystemAdapter($driver, $adapter);
        });

    }
}
