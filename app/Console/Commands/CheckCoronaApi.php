<?php

namespace App\Console\Commands;

use App\Notifications\CoronaNews;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Http;

class CheckCoronaApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:coronawerte';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Holt die aktuellen Coronazahlen vom RKI und schickt eine Slacknachricht';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $url = "https://services7.arcgis.com/mOBPykOjAyBO2ZKk/arcgis/rest/services/RKI_Landkreisdaten/FeatureServer/0/query?where=1%3D1&outFields=*&returnGeometry=false&outSR=4326&f=json";
        $response = Http::get($url);
        $data = $response->json();
        $landkreise = collect();
        foreach ($data['features'] as $ort) {
            $landkreise->push((object) $ort['attributes']);
        }
        $hannover = $landkreise->where('county', 'Region Hannover')->first();

        Notification::route('slack', env('SLACK_HOOK'))->notify(new CoronaNews($hannover));
    }
}
