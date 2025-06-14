<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::latest()->paginate(10);
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stock' => 'required|integer|min:0',
            'is_available' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('menus.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $request->except('image');
        $data['is_available'] = $request->has('is_available') ? 1 : 0;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menu_images', 'public');
            $data['image_url'] = $path;
        }

        Menu::create($data);
        return redirect()->route('menus.index')->with('success', 'Menu baru berhasil ditambahkan.');
    }

    public function show(Menu $menu)
    {
        return view('admin.menus.show', compact('menu'));
    }

    public function edit(Menu $menu)
    {
        return view('admin.menus.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'stock' => 'required|integer|min:0',
            'is_available' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('menus.edit', $menu->menu_id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $request->except('image');
        $data['is_available'] = $request->has('is_available') ? 1 : 0;

        if ($request->hasFile('image')) {
            if ($menu->image_url && Storage::disk('public')->exists($menu->image_url)) {
                Storage::disk('public')->delete($menu->image_url);
            }
            $path = $request->file('image')->store('menu_images', 'public');
            $data['image_url'] = $path;
        }

        $menu->update($data);
        return redirect()->route('menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->image_url && Storage::disk('public')->exists($menu->image_url)) {
            Storage::disk('public')->delete($menu->image_url);
        }
        $menu->delete();
        return redirect()->route('menus.index')->with('success', 'Menu berhasil dihapus.');
    }
}