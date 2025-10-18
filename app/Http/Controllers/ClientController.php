<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Interfaces\ClientInterface;
use App\Interfaces\UserInterface;
use App\Models\Client;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function __construct(
        private ClientInterface $repository,
    )
    {
    }

    public function index()
    {
        $clients = $this->repository->getAll();
        return inertia('Clients/Index', compact('clients'));
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
            DB::beginTransaction();
            $this->repository->create($request->validated());
            DB::commit();
            return back()->with('status', 'Client create successfully');
        
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
          try{
            DB::beginTransaction();
            $this->repository->update($client->id,$request->validated());
            DB::commit();
            return back()->with('status', 'Client updated successfully');
        }catch(\Exception $e){
            return back()->withError('errors', 'Action no Disabled');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        try{
            DB::beginTransaction();
            $this->repository->delete($client->id);
            DB::commit();
            return back()->with('status', 'Client delete successfully');
        }catch(\Exception $e){
            return back()->withError('errors', 'Action no Disabled');
        }
    }
}
