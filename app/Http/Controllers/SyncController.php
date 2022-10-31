<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SyncController extends Controller
{
    protected $client;
    public function __construct()
    {
        $client = new Google_Client();

        $client->setAuthConfig('client_secret.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);



        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
        $client->setHttpClient($guzzleClient);
        $this->client = $client;
    }
    public function syncevent(Request $request)
    {

        $google_id = $request['id'];
        $this->client->authenticate($google_id);
        $_SESSION['access_token'] = $this->client->getAccessToken();




        $calendarId = 'primary';
        $results = Event::where('user_id', '=', Auth::user()->id)
            ->where('google_id', '=', NULL)
            ->get();
        $this->client->setAccessToken($_SESSION['access_token']);
        $service = new Google_Service_Calendar($this->client);
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
        $results = Event::where('user_id', '=', Auth::user()->id)
            ->where('google_id', '=', NULL)
            ->delete();
        $this->client->setAccessToken($_SESSION['access_token']);
        $service = new Google_Service_Calendar($this->client);
        $results = $service->events->listEvents($calendarId);
        $google_events = $results->getItems();

        $items = Event::where('user_id', '=', Auth::user()->id)->get();
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
            $event->user_id = Auth::user()->id;
            $event->save();
        }
        $user = User::find(Auth::user()->id);
        $user->access_token = $_SESSION['access_token'];
        $user->save();
    }
    public function getallevent()
    {
        $allevents = Event::where('user_id', '=', Auth::user()->id)->get();
        return response()->json([
            "status" => true,
            "message" => "succesfully 2",
            "data" =>  $allevents,
        ]);
    }
}
