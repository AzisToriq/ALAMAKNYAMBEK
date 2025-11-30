<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan Import ini
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        // Tampilkan semua user, urutkan terbaru
        $users = User::latest()->get();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8', // Password minimal 8 karakter
            'role' => 'required|in:owner,admin,cashier',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password
            'role' => $request->role,
            'is_active' => true, // Default aktif saat dibuat
        ]);

        return back()->with('success', 'User baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:owner,admin,cashier',
            'is_active' => 'required|boolean',
        ]);

        // Data yang akan diupdate
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->is_active,
        ];

        // Cek apakah password diganti? (Jika kosong, pakai password lama)
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Data user diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // PERBAIKAN DI SINI:
        // Menggunakan Auth::id() agar tidak merah di VS Code
        if ($user->id == Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }
}