<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadStatesCities extends Command
{
    protected $signature = 'download:states-cities';
    protected $description = 'Download states + cities JSON data from GitHub';

    public function handle()
    {
        $url = 'https://raw.githubusercontent.com/dr5hn/countries-states-cities-database/refs/heads/master/json/states%2Bcities.json';
        
        $this->info('Downloading data...');
        $response = Http::get($url);

        if ($response->successful()) {
            Storage::put('data/states+cities.json', $response->body());
            $this->info('✅ Download complete! File saved to storage/app/data/states+cities.json');
        } else {
            $this->error('❌ Download failed: ' . $response->status());
        }
    }
}
