<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::orderByDesc('id')->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = Warehouse::select('id', 'name')->get();
        return view('users.create', compact('warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required",
            "email" => "required|unique:users,email",
            "phone" => "required",
            "password" => "required|confirmed",
            "warehouses" => "required|array",
            "users" => "nullable",
            "mng_warehouses" => "nullable",
            "suppliers" => "nullable",
            "customers" => "nullable",
            "products" => "nullable",
            "purchases" => "nullable",
            "rates" => "nullable",
            "recive_purchases" => "nullable",
            "inventory" => "nullable",
            "sales" => "nullable",
            "reports" => "nullable",
        ]);
        
        $validated = collect($validated)->except('warehouses')->toArray();
        $user = User::create($validated);
        $user->warehouses()->attach($request->warehouses);
        Log::create([
            "user_id" => auth()->id(),
            "details" => "تم انشاء المستخدم " . $user->name,
        ]);

        return redirect()->route('users.index')->with('success', ['تم إنشاء مستخدم جديد بنجاح']);
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
        $warehouses = Warehouse::all();
        return view('users.edit', compact('user','warehouses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            "name" => "required",
            "email" => "required|unique:users,email," . $user->id,
            "phone" => "required",
            "warehouses" => "required|array",
            "users" => "nullable",
            "mng_warehouses" => "nullable",
            "suppliers" => "nullable",
            "customers" => "nullable",
            "products" => "nullable",
            "purchases" => "nullable",
            "rates" => "nullable",
            "recive_purchases" => "nullable",
            "inventory" => "nullable",
            "sales" => "nullable",
            "reports" => "nullable",
        ]);
    
        // Remove the password field if it is not present in the request
        if (!$request->has('password')) {
            unset($validated['password']);
        }
    
        $validated = collect($validated)->except('warehouses')->toArray();
    
        $user->update($validated);
        $user->warehouses()->sync($request->warehouses);
    
        Log::create([
            "user_id" => auth()->id(),
            "details" => "تم تحديث المستخدم " . $user->name,
        ]);
    
        return redirect()->route('users.index')->with('success', ['تم تحديث المستخدم بنجاح']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->active = 0;
        $user->delete();
        return redirect()->route('users.index')->with('success', ['تم حذف المستخدم  بنجاح']);
    }

    public function active(User $user) {
        $user->active = !$user->active;
        $user->save();
        Log::create([
            "user_id" => auth()->id(),
            "details" => "تم تغيير حاله المستخدم " . $user->name,
        ]);
        return redirect()->back()->with('success', ['تم تغيير حاله المستخدم بنجاح']);
    }
}
