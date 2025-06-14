<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'address' => ['nullable', 'string'],
            'role' => ['required', Rule::in(['customer', 'cashier', 'admin'])],
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', Rule::unique('users')->ignore($user->user_id, 'user_id')],
            'phone' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'address' => ['nullable', 'string'],
            'role' => ['required', Rule::in(['customer', 'cashier', 'admin'])],
        ]);

         if ($validator->fails()) {
            return redirect()->route('users.edit', $user->user_id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $dataToUpdate = $request->only(['name', 'email', 'phone', 'address', 'role']);

        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($request->password);
        }

        $user->update($dataToUpdate);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('users.index')->with('error', 'Gagal menghapus pengguna. Pengguna ini masih terkait dengan data pesanan atau ulasan.');
        }
    }
}