<?php

namespace App\Providers;

use Storage;
use League\Flysystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Aws\S3\S3Client;
use App\Helpers\AwsS3Adapter;

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
                'endpoint' => 'https://' . env('AWS_ENDPOINT')
            ]);

            $bucket = env('AWS_BUCKET');
            
            return new Filesystem(new AwsS3Adapter($client, $bucket));
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
