<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Repositories\BrandRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{

    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $brandRepository = new BrandRepository($this->brand);

        //get carModel attributes
        if ($request->has('attribute_car_model')) {
            $attributes = 'carModel:id,' . $request->attribute_car_model;
            $brandRepository->selectAttributes($attributes);
        } else {
            $brandRepository->selectAttributes('carModel');
        }

        //multiple filter
        if ($request->has('filter')) {
            $brandRepository->filter($request->filter);

        }

        //get brand attributes
        if ($request->has('params')) {
            $brandRepository->selectParams($request->params);
        }

        return response()->json($brandRepository->getAttribute(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->brand->rules());

        $image = $request->file('image');
        $imageUrn = $image->store('images', 'public');

        $brand = $this->brand->create([
            'name' => $request->name,
            'image' => $imageUrn,
        ]);

        return response()->json(['Success' => true, 'brand' => $brand], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Interger $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand = $this->brand->with('carModel')->find($id);
        if ($brand === null) {
            return response()->json(['Erro' => 'Brand not found'], 404);
        }
        return response()->json($brand, 200);
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
        $brand = $this->brand->find($id);

        if ($brand === null) {
            return response()->json(['Erro' => 'Brand not found'], 404);
        }

        if ($request->method() === 'PATCH') {
            $dinamicRules = array();

            foreach ($brand->rules() as $input => $rules) {
                if (array_key_exists($input, $request->all())) {
                    $dinamicRules[$input] = $rules;
                }
            }
            $request->validate($dinamicRules);

        } else {
            $request->validate($brand->rules());
        }

        if ($request->file('image')) {
            //remove old image
            Storage::disk('public')->delete($brand->image);
            $image = $request->file('image');
            $imageUrn = $image->store('images', 'public');
        }

        $brand->fill($request->all());
        $request->file('image') ? $imageUrn : '';

        $brand->save();

        return response()->json(['Success' => true, $brand], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Interger $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = $this->brand->find($id);

        if ($brand === null) {
            return response()->json(['Erro' => 'Brand not found'], 404);
        }

        //remove old image
        Storage::disk('public')->delete($brand->image);
        $brand->delete();

        return response()->json([
            'Ok' => 'Brand deleted successfully',
            'Brand' => [
                'id' => $brand->id,
                'name' => $brand->name,
            ],
        ], 200);
    }
}
