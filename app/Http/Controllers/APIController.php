<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\DonorAPI;

class APIController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sync(Request $request)
    {
        $api = new DonorAPI('http://172.16.25.20/', 'api1', 'Api1Rand0M');
        dd($api->getDonors());
    }

}
