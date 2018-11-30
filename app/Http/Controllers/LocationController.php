<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Model\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Location::orderBy('id','desc')->get();

            return response()->json(['data' => $data], 200);
        } else {
            $uri = Route::current()->uri;

            return view('create-location',compact('uri'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocationRequest $request)
    {
           $data = Location::create([
               'name' => $request['name'],
               'lat' => $request['lat'],
               'lng' => $request['lng']
           ]);

        return response()->json($data, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Location::findOrFail($id);

        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(LocationRequest $request, $id)
    {
        Location::find($id)
            ->update([
                'name' => $request['name'],
                'lat' => $request['lat'],
                'lng' => $request['lng']
            ]);

        return response()->json(["valid" => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Location::destroy($id);

        return response()->json($data, 200);
    }
}
