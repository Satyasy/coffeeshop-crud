<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Menu::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }
        if ($request->has('is_available')) {
            $query->where('is_available', filter_var($request->is_available, FILTER_VALIDATE_BOOLEAN));
        }

        $menus = $query->latest('menu_id')->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Daftar Data Menu',
            'data' => $menus
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:150|unique:menus,name',
            'description'  => 'nullable|string', // Kolom description dari skema
            'price'        => 'required|numeric|min:0',
            'category'     => 'nullable|string|max:50',
            'stock'        => 'required|integer|min:0',
            'is_available' => 'sometimes|boolean',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048' // Nama input file 'image'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        // PERBAIKAN: Ambil data yang sudah divalidasi
        $data = $validator->validated();

        if ($request->hasFile('image')) {
            // Simpan file di 'storage/app/public/menu_images'
            $filePath = $request->file('image')->store('menu_images', 'public');
            // Simpan path ke kolom 'image_url'
            $data['image_url'] = $filePath;
        }

        // Hapus 'image' dari array karena tidak ada kolomnya di DB
        unset($data['image']);
        // Set nilai default 'is_available' jika tidak dikirim
        $data['is_available'] = $request->boolean('is_available', true);

        $menu = Menu::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil ditambahkan',
            'data'    => $menu
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail Menu',
            'data'    => $menu
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     * Note: Untuk update via API, biasanya menggunakan method POST dengan _method=PUT/PATCH
     */
    public function update(Request $request, Menu $menu)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'sometimes|required|string|max:150|unique:menus,name,' . $menu->menu_id . ',menu_id',
            'description'  => 'sometimes|nullable|string',
            'price'        => 'sometimes|required|numeric|min:0',
            'category'     => 'sometimes|nullable|string|max:50',
            'stock'        => 'sometimes|required|integer|min:0',
            'is_available' => 'sometimes|boolean',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi Gagal',
                'errors'  => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            if ($menu->image_url && Storage::disk('public')->exists($menu->image_url)) {
                Storage::disk('public')->delete($menu->image_url);
            }
            $filePath = $request->file('image')->store('menu_images', 'public');
            $data['image_url'] = $filePath;
        }
        
        unset($data['image']);
        if ($request->has('is_available')) {
            $data['is_available'] = $request->boolean('is_available');
        }

        $menu->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil diperbarui',
            'data'    => $menu
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        if ($menu->image_url && Storage::disk('public')->exists($menu->image_url)) {
            Storage::disk('public')->delete($menu->image_url);
        }
        $menu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil dihapus'
        ], 204); // 204 No Content adalah response yang lebih tepat untuk delete
    }
}