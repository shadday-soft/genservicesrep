<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ClientController extends Controller
{
    use AuthorizesRequests;

     public function __construct()
    {
        //Llama al método authorizeResource para aplicar las políticas de autorización
        $this->authorizeResource(Client::class, 'user');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $validated = $request->validated();
        try {
            Client::create($validated);
        } catch (Exception $e) {
            return back()->withErrors('message', 'Error al crear usuario: ' . $e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client)
    {
        $validated = $request->validated();
        try {
            $client->update($validated);
        } catch (Exception $e) {
            return back()->withErrors('message', 'Error al actualizar usuario: ' . $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        try {
            $client->delete();
        } catch (Exception $e) {
            return back()->withErrors('message', 'Error al eliminar usuario: ' . $e);
        }
    }
}
