<?php

namespace App\Http\Controllers;

use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarModelController extends Controller
{
    protected $carModel;
    public function __construct(CarModel $carModel)
    {
        $this->carModel = $carModel;
    }

    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $parameters = array();

        //get attr to brand
        if ($request->has('attribute_brand')) {
            $attribute_brand = $request->attribute_brand;
            $parameters = $this->carModel->with('brand:id,' . $attribute_brand);

        } else {
            $parameters = $this->carModel->with('brand');
        }

        //multiple filter search
        if ($request->has('filter')) {

            $filter = explode(';', $request->filter);
            foreach ($filter as $key => $value) {
                $condition = explode(':', $value);
                $parameters = $parameters->where($condition[0], $condition[1], $condition[2]);
            }

        }

        //get params from attr to CarModel;
        if ($request->has('params')) {
            $params = $request->params;
            $parameters = $parameters->selectRaw($params)->get();

        } else {
            $parameters = $parameters->get();
        }

        return response()->json($parameters, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->carModel->rules());

        $image = $request->file('image');
        $imageUrn = $image->store('images/carModel', 'public');

        $carModel = $this->carModel->create([
            'brand_id' => $request->brand_id,
            'name' => $request->name,
            'image' => $imageUrn,
            'num_doors' => $request->num_doors,
            'places' => $request->places,
            'airbag' => $request->airbag,
            'abs' => $request->abs,
        ]);
        return response()->json($carModel, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Interger  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $carModel = $this->carModel->with('brand')->find($id);

        if ($carModel === null) {
            return response()->json(['Erro' => 'Car model not found'], 404);
        }

        return response()->json($carModel, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Interger $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $carModel = $this->carModel->find($id);

        if ($carModel === null) {
            return response()->json(['Erro' => 'Car model not found'], 404);
        }
        if ($request->method() === 'PATCH') {
            $dinamicArray = array();
            foreach ($carModel->rules() as $key => $value) {
                if (array_key_exists($key, $request->all())) {
                    $dinamicArray[$key] = $value;
                }
            }
            $request->validate($dinamicArray);
        } else {
            $request->validate($carModel->rules());
        }

        if ($request->file('image')) {
            //remove old image
            Storage::disk('public')->delete($carModel->image);
            $image = $request->file('image');
            $imageUrn = $image->store('image/carModel', 'public');
        }

        $carModel->fill($request->all());
        $request->file('image') ? $imageUrn : '';

        $carModel->save();

        return response()->json(['Success' => true, 'Car_model' => $carModel]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Interger $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $carModel = $this->carModel->find($id);
        if ($carModel === null) {
            return response()->json(['Erro' => 'Car model not found'], 404);
        }

        Storage::disk('public')->delete($carModel->image);

        $carModel->delete();
        return response()->json([
            'Ok' => 'Car model deleted successfully',
            'car_model' => [
                'id' => $carModel->id,
                'name' => $carModel->name,
            ],
        ], 200);
    }
}
