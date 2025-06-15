<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::latest()->paginate(10);
        // PERBAIKAN: Mengarah ke 'menus.index' (tanpa admin)
        return view('menus.index', compact('menus'));
    }

    public function create()
    {
        // PERBAIKAN: Mengarah ke 'menus.create' (tanpa admin)
        return view('menus.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150|unique:menus,name',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stock' => 'required|integer|min:0',
            'is_available' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            // PERBAIKAN: Redirect ke 'menus.create' (tanpa admin)
            return redirect()->route('admin.menus.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menu_images', 'public');
            $data['image_url'] = $path;
        }

        unset($data['image']);
        $data['is_available'] = $request->has('is_available') ? 1 : 0;

        Menu::create($data);

        // PERBAIKAN: Redirect ke 'menus.index' (tanpa admin)
        return redirect()->route('admin.menus.index')->with('success', 'Menu baru berhasil ditambahkan.');
    }

    public function show(Menu $menu)
    {
        // PERBAIKAN: Mengarah ke 'menus.show' (tanpa admin)
        return view('menus.show', compact('menu'));
    }

    public function edit(Menu $menu)
    {
        // PERBAIKAN: Mengarah ke 'menus.edit' (tanpa admin)
        return view('menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:150', Rule::unique('menus')->ignore($menu->menu_id, 'menu_id')],
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stock' => 'required|integer|min:0',
            'is_available' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            // PERBAIKAN: Redirect ke 'menus.edit' (tanpa admin)
            return redirect()->route('menus.edit', $menu->menu_id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            if ($menu->image_url && Storage::disk('public')->exists($menu->image_url)) {
                Storage::disk('public')->delete($menu->image_url);
            }
            $path = $request->file('image')->store('menu_images', 'public');
            $data['image_url'] = $path;
        }

        unset($data['image']);
        $data['is_available'] = $request->has('is_available') ? 1 : 0;

        $menu->update($data);

        // PERBAIKAN: Redirect ke 'menus.index' (tanpa admin)
        return redirect()->route('menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->image_url && Storage::disk('public')->exists($menu->image_url)) {
            Storage::disk('public')->delete($menu->image_url);
        }
        $menu->delete();

        // PERBAIKAN: Redirect ke 'menus.index' (tanpa admin)
        return redirect()->route('menus.index')->with('success', 'Menu berhasil dihapus.');
    }
}