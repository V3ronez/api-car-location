<?php

namespace App\Http\Controllers;


use App\Models\Client;
use App\Repositories\ClientRepository;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carRepository = new ClientRepository($this->client);

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
     * @param  \illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->client->rules());

        $client = $this->client->create([
            'name' => $request->name,

        ]);

        return response()->json(['Success' => true, 'Client' => $client], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = $this->client->find($id);
        if($client === null) {
            return response()->json(['Erro' => 'Client not found'], 404);
        }
        return response()->json(['Success' => 'true', 'Client' => $client], 200);
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
        $client = $this->client->find($id);

        if ($client === null) {
            return response()->json(['Erro' => 'Client model not found'], 404);
        }
        if ($request->method() === 'PATCH') {
            $dinamicArray = array();
            foreach ($client->rules() as $key => $value) {
                if (array_key_exists($key, $request->all())) {
                    $dinamicArray[$key] = $value;
                }
            }
            $request->validate($dinamicArray);
        } else {
            $request->validate($client->rules());
        }

        $client->fill($request->all());
        $client->save();

        return response()->json(['Success' => true, 'Car_model' => $client]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = $this->client->find($id);
        if ($client === null) {
            return response()->json(['Erro' => 'Car model not found'], 404);
        }

        $client->delete();
        return response()->json([
            'Success' => 'Client deleted successfully',
            'Client' => [
                'id' => $client->id,
                'name' => $client->name,
            ],
        ], 200);
    }
}
