<?php

namespace App\Http\Controllers;


use App\Models\Car;
use App\Repositories\CarRepository;
use Illuminate\Http\Request;

class CarController extends Controller
{

    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    /**
     * Display a listing of the resource.
     *@param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carRepository = new CarRepository($this->car);

        //get carModel attributes
        if ($request->has('attributes')) {
            $attribute = 'carModel:id,' . $request->attributes;
            $carRepository->selectAttributes($attribute);
        } else {
            $carRepository->selectAttributes('carModel');
        }

        if ($request->has('filter')) {
            $carRepository->filter($request->filter);
        }

        if ($request->has('params')) {
            $carRepository->selectParams($request->params);
        }

        return response()->json($carRepository->getAttribute(), 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->car->rules());

        $car = $this->car->create([
            'car_model_id' => $request->car_model_id,
            'license_plate' => $request->license_plate,
            'available' => $request->available,
            'km' => $request->km,
        ]);

        return response()->json(['Success' => true, 'Car' => $car], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  id $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $car = $this->car->with('carModel')->find($id);
        if($car === null) {
            return response()->json(['Erro' => 'Car not found'], 404);
        }
        return response()->json($car, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param   \Illuminate\Http\Request  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $car = $this->car->find($id);

        if ($car === null) {
            return response()->json(['Erro' => 'Car not found'], 404);
        }

        if ($request->method() === 'PATCH') {
            $dinamicRules = array();

            foreach ($car->rules() as $input => $rules) {
                if (array_key_exists($input, $request->all())) {
                    $dinamicRules[$input] = $rules;
                }
            }

            $request->validate($dinamicRules);
        } else {
            $request->validate($car->rules());
        }


        $car->fill($request->all());
        $car->save();

        return response()->json(['Success' => true, "Car" => $car], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $car = $this->car->find($id);

        if ($car === null) {
            return response()->json(['Erro' => 'Car not found'], 404);
        }

        return response()->json([
            'Success' => 'Car deleted successfully',
            'Car' => $car,
        ], 200);
    }
}
