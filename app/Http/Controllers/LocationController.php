<?php

namespace App\Http\Controllers;


use App\Models\location;
use App\Repositories\LocationRepository;
use Illuminate\Http\Request;

class LocationController extends Controller

{

    public function __construct(Location $location)
    {
        $this->location = $location;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locationRepository = new LocationRepository($this->location);

        if ($request->has('filter')) {
            $locationRepository->filter($request->filter);
        }

        if ($request->has('params')) {
            $locationRepository->selectParams($request->params);
        }

        return response()->json($locationRepository->getAttribute(), 200);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->location->rules());

        $location = $this->location->create([
            'clients_id' => $request->clients_id,
            'car_id' => $request->car_id,
            'date_start_route' => $request->date_start_route,
            'date_final_undefined_route' => $request->date_final_undefined_route,
            'date_final_defined_route' => $request->date_final_defined_route,
            'price_daily' => $request->price_daily,
            'km_start' => $request->km_start,
            'km_final' => $request->km_final,

        ]);

        return response()->json(['Success' => true, 'Location' => $location], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $location = $this->location->find($id);
        if($location === null) {
            return response()->json(['Erro' => 'Location not found'], 404);
        }
        return response()->json(['Success' => 'true', 'Location' => $location], 200);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $location = $this->location->find($id);

        if ($location === null) {
            return response()->json(['Erro' => 'Location not found'], 404);
        }
        if ($request->method() === 'PATCH') {
            $dinamicArray = array();
            foreach ($location->rules() as $key => $value) {
                if (array_key_exists($key, $request->all())) {
                    $dinamicArray[$key] = $value;
                }
            }
            $request->validate($dinamicArray);
        } else {
            $request->validate($location->rules());
        }

        $location->fill($request->all());
        $location->save();

        return response()->json(['Success' => true, 'Location' => $location]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $location = $this->Location->find($id);
        if ($location === null) {
            return response()->json(['Erro' => 'Location not found'], 404);
        }

        $location->delete();
        return response()->json([
            'Success' => 'Location deleted successfully',
            'Location' => $location,
        ], 200);
    }
}
