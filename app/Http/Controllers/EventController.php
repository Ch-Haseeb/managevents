<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function storeevent(Request $request)
    {

        $appoitment = new Event();
        $appoitment->name = $request->input('name');
        $appoitment->date = $request->input('meeting_date');
        $appoitment->time = $request->input('meeting_time');
        $appoitment->user_id = Auth::user()->id;
        $appoitment->save();
        return response()->json([
            "status" => true,
            "message" => "Event Create succesfully",
            "data" => $appoitment,
        ]);
    }
}
