<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Interfaces\UserInterface;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct(
        private UserInterface $repository,
    ) {}

    public function index()
    {
        $users = $this->repository->getAll();
        if (request()->wantsJson()) {
            return response()->json([
                'users' => $users
            ]);
        }
        return inertia('Users/Index', compact('users'));
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
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->repository->create($request->validated());
            DB::commit();
            return back()->with('status', 'User create successfully');
        } catch (\Exception $e) {
            return back()->withError('errors', 'Action no Disabled');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        try {
            DB::beginTransaction();
            $this->repository->update($user->id, $request->validated());
            DB::commit();
            return back()->with('status', 'User updated successfully');
        } catch (\Exception $e) {
            return back()->withError('errors', 'Action no Disabled');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            DB::beginTransaction();
            $this->repository->delete($user->id);
            DB::commit();
            return back()->with('status', 'User delete successfully');
        } catch (\Exception $e) {
            return back()->withError('errors', 'Action no Disabled');
        }
    }
}
