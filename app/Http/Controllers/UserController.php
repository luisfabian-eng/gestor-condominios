<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('resident.unit.condominium')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $residents = Resident::with('unit')->get();
        return view('users.create', compact('residents'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:6',
            'role'        => 'required|in:admin,residente',
            'resident_id' => 'nullable|exists:residents,id',
        ]);

        User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'resident_id' => $request->resident_id,
        ]);

        return redirect()->route('users.index')->with('status', 'Usuario creado correctamente.');
    }

    public function edit(User $user)
    {
        $residents = Resident::with('unit')->get();
        return view('users.edit', compact('user', 'residents'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'role'        => 'required|in:admin,residente',
            'resident_id' => 'nullable|exists:residents,id',
        ]);

        $data = [
            'name'        => $request->name,
            'email'       => $request->email,
            'role'        => $request->role,
            'resident_id' => $request->resident_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('status', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('status', 'Usuario eliminado correctamente.');
    }
}
