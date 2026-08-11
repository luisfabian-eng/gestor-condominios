<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Resident;
use App\Models\Unit;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Muestra el formulario de registro pasando las unidades disponibles.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showRegistrationForm()
    {
        // Trae las unidades que aún no tienen residente asignado
        $units = Unit::doesntHave('resident')
            ->with('condominium')
            ->orderBy('number')
            ->get();

        return view('auth.register', compact('units'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'unit_id'  => ['required', 'exists:units,id'],
            'rut'      => ['nullable', 'string', 'max:20'],
            'phone'    => ['nullable', 'string', 'max:20'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // 1. Creamos primero el registro en la tabla residentes
        $resident = Resident::create([
            'unit_id' => $data['unit_id'],
            'name'    => $data['name'],
            'rut'     => $data['rut'] ?? null,
            'email'   => $data['email'],
            'phone'   => $data['phone'] ?? null,
        ]);

        // 2. Creamos la cuenta de usuario vinculando el resident_id
        return User::create([
            'name'        => $data['name'],
            'email'       => $data['email'],
            'password'    => Hash::make($data['password']),
            'role'        => 'residente',
            'resident_id' => $resident->id,
        ]);
    }
}
