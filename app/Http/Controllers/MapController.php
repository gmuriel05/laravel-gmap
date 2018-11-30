<?php

namespace App\Http\Controllers;

use App\Model\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class MapController extends Controller
{

    public function getMap(Request $request){

        if ($request->ajax()) {
            $data = Location::all();

            return response()->json($data, 200);
        } else {
            $uri = Route::current()->uri;

            return view('map', compact('uri'));
        }
    }


}
