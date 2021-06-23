<?php

namespace App\Console\Commands;

use App\Notifications\CoronaNews as NotificationsCoronaNews;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;

class CoronaNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coronanews:abruf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eine Beschreibung';

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
        foreach ($data['features'] as $landkreis) {
            $landkreise->push((object) $landkreis['attributes']);
        }
        $hannover = $landkreise->where('county', 'Region Hannover')->first();
        // $ansbach = $landkreise->where('county', 'SK Ansbach')->first();

        Notification::route('slack', env('SLACK_HOOK'))->notify(new NotificationsCoronaNews($hannover));
    }
}
