<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Illuminate\Support\Facades\Session;


class GoogleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

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
    public function index()
    {
        // session_start();
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $calendarId = 'primary';

            $results = $service->events->listEvents($calendarId);
            $google_events = $results->getItems();
            // $user_id=Auth::user()->id;
            foreach ($results->getItems() as $google_event) {
                $event = new Event();
                $event->name = $google_event->summary;
                $event->google_id = $google_event->id;
                $variable = explode("T", $google_event->start->dateTime);
                $event->date = $variable[0];
                $event->time = $variable[1];
                $event->user_id = 1;
                $event->save();
            }
        } else {
            return $this->oauth();
        }
    }
    public function oauth()
    {
        session_start();


        // $action=$this->oauth();

        // $rurl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        $rurl = 'http://localhost:8000/event';
        // $rurl=$this->oauth();
        // $rurl='http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
        // $rurl='http://localhost:8000/index.php/api/calandar';
        $this->client->setRedirectUri($rurl);
        // $this->client->setRedirectUri($rurl);
        // $this->client->setRedirectUri('http://localhost:8000/event' . '/oauth2callback');
        if (!isset($_GET['code'])) {
            $auth_url = $this->client->createAuthUrl();
            $filtered_url = filter_var($auth_url, FILTER_SANITIZE_URL);
            return response()->json([
                "status" => true,
                "message" => "succesfully 2",
                "data" => $filtered_url,
            ]);
        } else {
            $this->client->authenticate($_GET['code']);
            $_SESSION['access_token'] = $this->client->getAccessToken();
            return response()->json([
                "status" => true,
                "message" => "succesfully 2",
                "data" =>  $_GET['code'],
            ]);

            return $this->index();
        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
