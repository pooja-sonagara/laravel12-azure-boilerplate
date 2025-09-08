<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Azure\Provider;

use AzureOss\Storage\Blob\BlobContainerClient;
use AzureOss\Storage\Common\Auth\StorageSharedKeyCredential;
use AzureOss\FlysystemAzureBlobStorage\AzureBlobStorageAdapter;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Illuminate\Notifications\ChannelManager;
use App\Channels\SignalRChannel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(SocialiteWasCalled::class, function ($event) {
            $event->extendSocialite('azure', Provider::class);
        });


        Storage::extend('azure', function ($app, $config) {
            $accountName = $config['name'];
            $accountKey = $config['key'];
            $container = $config['container'];
            $prefix = $config['prefix'] ?? null;

            $endpoint = "https://$accountName.blob.core.windows.net/$container";
            $uri = new Uri($endpoint);

            $credential = new StorageSharedKeyCredential($accountName, $accountKey);
            $client = new BlobContainerClient($uri, $credential);

            $mimeTypeDetector = new \League\MimeTypeDetection\FinfoMimeTypeDetector();

            $adapter = new AzureBlobStorageAdapter(
                $client,              // 1. BlobContainerClient
                $prefix ?? '',        // 2. Prefix string (can be empty)
                $mimeTypeDetector     // 3. MimeTypeDetector instance
                // 4th param is optional (visibility handling string)
            );

            return new \Illuminate\Filesystem\FilesystemAdapter(
                new Filesystem($adapter),
                $adapter,
                $config
            );
        });

        $this->app->make(ChannelManager::class)->extend('signalr', function ($app) {
            return new SignalRChannel();
        });
    }
}
