<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Illuminate\Support\Carbon;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AutoSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '=auto:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto Sync Event Successfully';

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

        $users=User::all();
        foreach($users as $user){

        $access = $user->access_token;
        if ($access) 
    {
        $user_id=$user->id;
    
        $client = new Google_Client();
        $client->setAuthConfig(public_path('client_secret.json'));
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
        $client->setHttpClient($guzzleClient);
        $client->setAccessType('offline');
        $calendarId = 'primary';
        $results = Event::where('user_id', '=',$user_id )
            ->where('google_id', '=', NULL)
            ->get();
        $client->setAccessToken($access);
        $service = new Google_Service_Calendar($client);
        foreach ($results as $result) {
            $starttime = Carbon::parse($result['date'] . ' ' . $result['time'], 'Asia/Karachi');
            $endtime = (clone $starttime)->addHour();
            $calendarId = 'primary';
            $event = new Google_Service_Calendar_Event([
                'summary' => $result->name,

                'start' => ['dateTime' => $starttime],
                'end' => ['dateTime' => $endtime],
            ]);
            $addevent = $service->events->insert($calendarId, $event);
        }
        $results = Event::where('user_id', '=', $user_id)
            ->where('google_id', '=', NULL)
            ->delete();
        $client->setAccessToken($access);
        $service = new Google_Service_Calendar($client);
        $results = $service->events->listEvents($calendarId);
        $google_events = $results->getItems();

        $items = Event::where('user_id', '=', $user_id)->get();
        $notReadyItems = [];
        foreach ($google_events as $event) {
            $itemReady = false;
            foreach ($items as $item) {
                if ($event->id === $item->google_id) {
                    $itemReady = true;
                    break;
                }
            }
            if ($itemReady === false) {
                $notReadyItems[] = $event;
            }
        }

        foreach ($notReadyItems as $google_event) {
            $event = new Event();
            $event->name = $google_event->summary;
            $event->google_id = $google_event->id;
            $variable = explode("T", $google_event->start->dateTime);
            $event->date = $variable[0];
            $event->time = $variable[1];
            $event->user_id = $user_id;
            $event->save();
        }
    }
    }
    }
}
