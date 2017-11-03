<?php

namespace App\Providers;

use Storage;
use League\Flysystem\Filesystem;
use Aws\S3\S3Client;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\AwsS3v3\AwsS3Adapter as S3Adapter;

class BizmrgServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend('bizmrg', function ($app, $config) {
            
            $client = new S3Client([
                'version'     => 'latest',
                'region'      => 'us-west-2',
                'credentials' => [
                    'key'    => env('AWS_KEY'),
                    'secret' => env('AWS_SECRET'),
                ],
                'endpoint' => 'https://hb.bizmrg.com'
            ]);

            $bucket = env('AWS_BUCKET');
            
            return new Filesystem(new S3Adapter($client, $bucket));
        });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
