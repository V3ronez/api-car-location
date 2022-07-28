<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{

    public function __construct(Brand $brand)
    {
        $this->brand = $brand;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = $this->brand->all();
        return response()->json($brands, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->brand->validate());

        $brand = $this->brand->create($request->all());
        return response()->json($brand, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Interger $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand = $this->brand->find($id);
        if ($brand === null) {
            return response()->json(['Erro' => 'User not found'], 404);
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
            return response()->json(['Erro' => 'User not found'], 404);
        }
        $brand->update($request->all());

        return response()->json($brand, 200);
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
            return response()->json(['Erro' => 'User not found'], 404);
        }
        $brand->delete();

        return response()->json(['Ok' => 'User deleted successfully'], 200);
    }
}
