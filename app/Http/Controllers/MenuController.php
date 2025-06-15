<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan halaman daftar menu.
     */
    public function index()
    {
        // Mengambil data menu terbaru dengan paginasi
        $menus = Menu::latest()->paginate(10);
        return view('menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form untuk membuat menu baru.
     */
    public function create()
    {
        return view('menus.create');
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan menu baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:menus,name',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menus', 'public');
            $validatedData['image'] = $path;
        }

        Menu::create($validatedData);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu created successfully.');
    }

    /**
     * Display the specified resource.
     * Menampilkan detail satu menu.
     */
    public function show(Menu $menu)
    {
        return view('menus.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form untuk mengedit menu.
     */
    public function edit(Menu $menu)
    {
        return view('menus.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui data menu di database.
     */
    public function update(Request $request, Menu $menu)
    {
        // Menampung hasil validasi ke dalam variabel $validatedData
        $validatedData = $request->validate([
            // Mengabaikan ID menu yang sedang diedit saat validasi unik
            'name' => 'required|string|max:255|unique:menus,name,' . $menu->menu_id . ',menu_id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Gambar boleh kosong saat update
        ]);

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            // Simpan gambar baru
            $path = $request->file('image')->store('menus', 'public');
            $validatedData['image'] = $path;
        }

        $menu->update($validatedData);

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus data menu dari database.
     */
    public function destroy(Menu $menu)
    {
        // Hapus gambar dari storage jika ada
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        // Hapus data dari database
        $menu->delete();

        // **PERBAIKAN UTAMA ADA DI SINI**
        // Redirect kembali ke halaman DAFTAR (index), bukan ke halaman detail.
        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu deleted successfully.');
    }
}